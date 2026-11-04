<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\NotificationLog;
use App\Models\Practitioner;
use App\Models\PractitionerAvailability;
use App\Models\PractitionerLeave;
use App\Models\Service;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Get available booking slots for a practitioner on a specified date.
     *
     * @return array<int, string> Time slots in 'H:i' (e.g. ['09:00', '10:00', '11:00'])
     */
    public function getAvailableSlots(int $practitionerId, string $dateString): array
    {
        $date = Carbon::parse($dateString);
        $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 1 = Monday...

        // 1. Check if practitioner is on approved leave
        $isOnLeave = PractitionerLeave::where('practitioner_id', $practitionerId)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();

        if ($isOnLeave) {
            return [];
        }

        // 2. Fetch practitioner's availability rule for this day of week
        $availability = PractitionerAvailability::where('practitioner_id', $practitionerId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return [];
        }

        $slotMinutes = $availability->slot_duration_minutes ?? 50;
        $bufferMinutes = 10;
        $totalInterval = $slotMinutes + $bufferMinutes;

        $start = Carbon::parse($date->toDateString() . ' ' . $availability->start_time);
        $end = Carbon::parse($date->toDateString() . ' ' . $availability->end_time);

        $breakStart = $availability->break_start ? Carbon::parse($date->toDateString() . ' ' . $availability->break_start) : null;
        $breakEnd = $availability->break_end ? Carbon::parse($date->toDateString() . ' ' . $availability->break_end) : null;

        // 3. Fetch existing booked appointments on this date
        $bookedAppointments = Appointment::where('practitioner_id', $practitionerId)
            ->whereDate('scheduled_at', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->get();

        $slots = [];
        $current = clone $start;

        while ($current->copy()->addMinutes($slotMinutes)->lte($end)) {
            $slotEnd = $current->copy()->addMinutes($slotMinutes);

            // Check if slot falls in lunch / break
            $inBreak = false;
            if ($breakStart && $breakEnd) {
                if ($current->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                    $inBreak = true;
                }
            }

            // Check if slot conflicts with any existing appointment
            $isBooked = false;
            if (!$inBreak) {
                foreach ($bookedAppointments as $apt) {
                    $aptStart = $apt->scheduled_at;
                    $aptEnd = $aptStart->copy()->addMinutes($apt->duration_minutes);

                    if ($current->lt($aptEnd) && $slotEnd->gt($aptStart)) {
                        $isBooked = true;
                        break;
                    }
                }
            }

            if (!$inBreak && !$isBooked) {
                $slots[] = $current->format('H:i');
            }

            $current->addMinutes($totalInterval);
        }

        return $slots;
    }

    /**
     * Create an appointment with race-condition safe locking and transaction.
     */
    public function bookAppointment(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            $practitionerId = (int) $data['practitioner_id'];
            $serviceId = (int) $data['service_id'];
            $scheduledAt = Carbon::parse($data['scheduled_at']);
            $tenantId = $data['tenant_id'] ?? (app()->has('current_tenant_id') ? app('current_tenant_id') : 1);

            $service = Service::findOrFail($serviceId);
            $durationMinutes = $data['duration_minutes'] ?? $service->duration_minutes;
            $fee = $data['fee'] ?? $service->price;

            // Double booking validation with lock
            $endTime = (clone $scheduledAt)->addMinutes($durationMinutes);

            $conflict = Appointment::where('tenant_id', $tenantId)
                ->where('practitioner_id', $practitionerId)
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->where(function ($q) use ($scheduledAt, $endTime) {
                    $q->whereBetween('scheduled_at', [$scheduledAt, $endTime]);
                })
                ->lockForUpdate()
                ->first();

            if ($conflict) {
                throw new \InvalidArgumentException("This practitioner is already booked for the selected time slot.");
            }

            // Find or create client
            $clientId = $data['client_id'] ?? null;
            if (!$clientId && !empty($data['client_email'])) {
                $client = Client::firstOrCreate(
                    [
                        'tenant_id' => $tenantId,
                        'email' => $data['client_email'],
                    ],
                    [
                        'first_name' => $data['first_name'] ?? 'Client',
                        'last_name' => $data['last_name'] ?? 'Member',
                        'phone' => $data['phone'] ?? null,
                        'preferred_language' => $data['preferred_language'] ?? 'en',
                        'status' => 'active',
                    ]
                );
                $clientId = $client->id;
            }

            $deliveryMode = $data['delivery_mode'] ?? $service->delivery_mode;
            if ($deliveryMode === 'hybrid') {
                $deliveryMode = $data['chosen_mode'] ?? 'in_person';
            }

            $appointment = Appointment::create([
                'tenant_id' => $tenantId,
                'branch_id' => $data['branch_id'] ?? null,
                'client_id' => $clientId,
                'practitioner_id' => $practitionerId,
                'service_id' => $serviceId,
                'scheduled_at' => $scheduledAt,
                'duration_minutes' => $durationMinutes,
                'status' => $data['status'] ?? 'confirmed',
                'delivery_mode' => $deliveryMode,
                'fee' => $fee,
                'payment_status' => $data['payment_status'] ?? 'unpaid',
                'admin_notes' => $data['admin_notes'] ?? null,
                'booking_source' => $data['booking_source'] ?? 'online',
            ]);

            // Automatically generate Invoice for this session
            $invoice = Invoice::create([
                'tenant_id' => $tenantId,
                'client_id' => $clientId,
                'appointment_id' => $appointment->id,
                'issue_date' => today(),
                'due_date' => $scheduledAt->toDateString(),
                'subtotal' => $fee,
                'tax_rate' => 0.00,
                'tax_amount' => 0.00,
                'discount_amount' => 0.00,
                'total_amount' => $fee,
                'paid_amount' => 0.00,
                'status' => 'sent',
                'notes' => "Invoice for {$service->name} scheduled on " . $scheduledAt->format('M j, Y g:i A'),
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => "{$service->name} ({$durationMinutes} min)",
                'quantity' => 1,
                'unit_price' => $fee,
                'total_price' => $fee,
            ]);

            // Log notification
            NotificationLog::create([
                'tenant_id' => $tenantId,
                'user_id' => auth()->id(),
                'type' => 'email',
                'event' => 'appointment_confirmation',
                'recipient' => $data['client_email'] ?? 'client@demo.mhcb.test',
                'subject' => "Appointment Confirmed: {$service->name}",
                'content' => "Your appointment is confirmed for " . $scheduledAt->format('l, F j, Y \a\t g:i A') . ". Reference: {$appointment->appointment_number}.",
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            // Log audit
            AuditLog::log('created', $appointment, [], $appointment->toArray(), $tenantId);

            return $appointment;
        });
    }
}
