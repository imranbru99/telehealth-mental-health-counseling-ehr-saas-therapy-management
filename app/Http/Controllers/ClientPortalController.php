<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\ConsentRecord;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientPortalController extends Controller
{
    protected function getClient()
    {
        $user = auth()->user();
        if (!$user) return null;

        // Try direct relationship, or match by email
        return $user->clientProfile ?? Client::where('email', $user->email)->first();
    }

    public function dashboard()
    {
        $client = $this->getClient();

        if (!$client) {
            return redirect()->route('booking.index')
                ->with('info', 'Please book your first appointment to establish your client profile.');
        }

        $upcomingAppointments = Appointment::with(['practitioner.user', 'service', 'branch'])
            ->where('client_id', $client->id)
            ->where('scheduled_at', '>=', now())
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('scheduled_at')
            ->take(3)
            ->get();

        $pastAppointments = Appointment::with(['practitioner.user', 'service'])
            ->where('client_id', $client->id)
            ->where('scheduled_at', '<', now())
            ->orderByDesc('scheduled_at')
            ->take(5)
            ->get();

        $pendingInvoices = Invoice::where('client_id', $client->id)
            ->where('status', '!=', 'paid')
            ->orderByDesc('due_date')
            ->get();

        $consentRecords = ConsentRecord::where('client_id', $client->id)
            ->orderByDesc('agreed_at')
            ->take(4)
            ->get();

        $unreadMessagesCount = Message::where('recipient_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $recentInvoices = $pendingInvoices;
        $consents = $consentRecords;

        return view('portal.dashboard', compact(
            'client',
            'upcomingAppointments',
            'pastAppointments',
            'pendingInvoices',
            'recentInvoices',
            'consentRecords',
            'consents',
            'unreadMessagesCount'
        ));
    }

    public function appointments()
    {
        $client = $this->getClient();
        $appointments = Appointment::with(['practitioner.user', 'service', 'branch'])
            ->where('client_id', $client->id)
            ->orderByDesc('scheduled_at')
            ->paginate(10);

        return view('portal.appointments', compact('client', 'appointments'));
    }

    public function cancelAppointment(Request $request, Appointment $appointment)
    {
        $client = $this->getClient();
        if ($appointment->client_id !== $client->id) {
            abort(403);
        }

        $request->validate(['cancellation_reason' => 'required|string|max:500']);

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->input('cancellation_reason'),
            'cancelled_at' => now(),
            'cancelled_by' => auth()->id(),
        ]);

        return back()->with('success', 'Your appointment has been cancelled.');
    }

    public function telehealth(Appointment $appointment)
    {
        $client = $this->getClient();
        if ($appointment->client_id !== $client->id && !auth()->user()->isAdmin() && !auth()->user()->isPractitioner()) {
            abort(403, 'Unauthorized to access this telehealth room.');
        }

        return view('portal.telehealth', compact('appointment'));
    }

    public function invoices()
    {
        $client = $this->getClient();
        $invoices = Invoice::with(['items', 'payments', 'appointment.service'])
            ->where('client_id', $client->id)
            ->orderByDesc('issue_date')
            ->paginate(10);

        return view('portal.invoices', compact('client', 'invoices'));
    }

    public function payInvoice(Request $request, Invoice $invoice)
    {
        $client = $this->getClient();
        if ($invoice->client_id !== $client->id) {
            abort(403);
        }

        $balance = $invoice->balance_due;
        if ($balance <= 0) {
            return back()->with('info', 'This invoice is already paid in full.');
        }

        // Process instant simulated payment in test environment
        Payment::create([
            'tenant_id' => $invoice->tenant_id,
            'invoice_id' => $invoice->id,
            'client_id' => $client->id,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
            'amount' => $balance,
            'payment_method' => $request->input('payment_method', 'stripe'),
            'status' => 'success',
            'paid_at' => now(),
        ]);

        $invoice->update([
            'paid_amount' => $invoice->total_amount,
            'status' => 'paid',
        ]);

        if ($invoice->appointment) {
            $invoice->appointment->update(['payment_status' => 'paid']);
        }

        return back()->with('success', "Payment of \${$balance} processed successfully. Thank you!");
    }

    public function documents()
    {
        $client = $this->getClient();

        // Clients can ONLY see documents explicitly marked as 'client_accessible'
        // Strict HIPAA / confidentiality safeguard!
        $documents = Document::where('client_id', $client->id)
            ->where('access_level', 'client_accessible')
            ->orderByDesc('created_at')
            ->get();

        return view('portal.documents', compact('client', 'documents'));
    }

    public function consents()
    {
        $client = $this->getClient();
        $consents = ConsentRecord::where('client_id', $client->id)
            ->orderByDesc('agreed_at')
            ->get();

        return view('portal.consents', compact('client', 'consents'));
    }

    public function storeConsent(Request $request)
    {
        $client = $this->getClient();
        $validated = $request->validate([
            'document_title' => 'required|string|max:150',
            'consent_type' => 'required|string|max:50',
            'signer_name' => 'required|string|max:100',
            'agreed' => 'accepted',
        ]);

        ConsentRecord::create([
            'tenant_id' => $client->tenant_id,
            'client_id' => $client->id,
            'guardian_user_id' => auth()->user()->isGuardian() ? auth()->id() : null,
            'consent_type' => $validated['consent_type'],
            'document_title' => $validated['document_title'],
            'document_text' => 'Standard Informed Consent for Mental Health Care and Telehealth Consultation.',
            'signer_name' => $validated['signer_name'],
            'ip_address' => $request->ip(),
            'status' => 'active',
            'agreed_at' => now(),
        ]);

        $client->update(['consent_signed' => true]);

        return back()->with('success', 'Informed consent signed and filed in your portal.');
    }

    public function messages()
    {
        $client = $this->getClient();
        $userId = auth()->id();

        $messages = Message::with(['sender', 'recipient'])
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('recipient_id', $userId);
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        // Mark incoming messages as read
        Message::where('recipient_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('portal.messages', compact('client', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        $client = $this->getClient();
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:150',
            'body' => 'required|string|max:2000',
        ]);

        Message::create([
            'tenant_id' => $client->tenant_id,
            'sender_id' => auth()->id(),
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'is_clinical' => false,
            'is_read' => false,
        ]);

        return back()->with('success', 'Your secure message has been delivered to your care team.');
    }

    public function profile()
    {
        $client = $this->getClient();
        return view('portal.profile', compact('client'));
    }

    public function updateProfile(Request $request)
    {
        $client = $this->getClient();
        $validated = $request->validate([
            'phone' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:25',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'preferred_language' => 'required|in:en,bn',
            'communication_preference' => 'required|in:email,sms,portal',
        ]);

        $client->update($validated);

        return back()->with('success', 'Your profile details have been saved.');
    }
}
