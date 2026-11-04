<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Practitioner;
use App\Models\Service;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService)
    {
    }

    public function index(Request $request)
    {
        $services = Service::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();
        $practitioners = Practitioner::with('user')->where('is_active', true)->get();

        $selectedService = $request->query('service_id') ? Service::find($request->query('service_id')) : null;
        $selectedPractitioner = $request->query('practitioner_id') ? Practitioner::find($request->query('practitioner_id')) : null;

        return view('public.booking.index', compact('services', 'branches', 'practitioners', 'selectedService', 'selectedPractitioner'));
    }

    /**
     * Ajax API: returns available time slots for practitioner and date.
     */
    public function slots(Request $request): JsonResponse
    {
        $request->validate([
            'practitioner_id' => 'required|integer',
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
        ]);

        $slots = $this->bookingService->getAvailableSlots(
            (int) $request->input('practitioner_id'),
            $request->input('date')
        );

        return response()->json([
            'success' => true,
            'date' => $request->input('date'),
            'slots' => $slots,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'practitioner_id' => 'required|exists:practitioners,id',
            'branch_id' => 'nullable|exists:branches,id',
            'scheduled_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'scheduled_time' => 'required|string',
            'delivery_mode' => 'required|in:in_person,online_telehealth',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:25',
            'preferred_language' => 'required|in:en,bn',
            'consent_agreed' => 'accepted',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:25',
            'intake_notes' => 'nullable|string|max:1000',
        ]);

        $scheduledAt = "{$validated['scheduled_date']} {$validated['scheduled_time']}:00";

        try {
            $appointment = $this->bookingService->bookAppointment([
                'service_id' => $validated['service_id'],
                'practitioner_id' => $validated['practitioner_id'],
                'branch_id' => $validated['branch_id'] ?? null,
                'scheduled_at' => $scheduledAt,
                'delivery_mode' => $validated['delivery_mode'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'client_email' => $validated['email'],
                'phone' => $validated['phone'],
                'preferred_language' => $validated['preferred_language'],
                'admin_notes' => $validated['intake_notes'] ?? null,
                'booking_source' => 'public_online',
            ]);

            return redirect()->route('booking.confirmation', ['appointment' => $appointment->id])
                ->with('success', 'Your appointment has been successfully scheduled!');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['scheduled_time' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['general' => 'Unable to complete appointment booking. ' . $e->getMessage()]);
        }
    }

    public function confirmation(\App\Models\Appointment $appointment)
    {
        $appointment->load(['client', 'practitioner.user', 'service', 'branch', 'invoice']);
        return view('public.booking.confirmation', compact('appointment'));
    }
}
