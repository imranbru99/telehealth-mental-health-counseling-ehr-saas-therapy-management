<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'client_id',
        'practitioner_id',
        'service_id',
        'appointment_number',
        'scheduled_at',
        'duration_minutes',
        'status',
        'delivery_mode',
        'telehealth_meeting_url',
        'telehealth_meeting_id',
        'telehealth_meeting_password',
        'cancellation_reason',
        'cancelled_at',
        'cancelled_by',
        'fee',
        'payment_status',
        'admin_notes',
        'booking_source',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'duration_minutes' => 'integer',
            'cancelled_at' => 'datetime',
            'fee' => 'float',
            'reminder_sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($apt) {
            if (empty($apt->appointment_number)) {
                $apt->appointment_number = 'APT-' . strtoupper(Str::random(8));
            }

            if ($apt->delivery_mode === 'online_telehealth' && empty($apt->telehealth_meeting_url)) {
                $roomId = 'mhcb-room-' . strtolower(Str::random(12));
                $apt->telehealth_meeting_id = $roomId;
                $apt->telehealth_meeting_url = 'https://meet.jit.si/' . $roomId;
                $apt->telehealth_meeting_password = Str::random(8);
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function therapySession(): HasOne
    {
        return $this->hasOne(TherapySession::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Check if a practitioner has a conflicting appointment
     */
    public static function checkDoubleBooking(
        int $tenantId,
        int $practitionerId,
        string $scheduledAt,
        int $durationMinutes,
        ?int $ignoreAppointmentId = null
    ): bool {
        $startTime = \Carbon\Carbon::parse($scheduledAt);
        $endTime = (clone $startTime)->addMinutes($durationMinutes);

        $query = static::where('tenant_id', $tenantId)
            ->where('practitioner_id', $practitionerId)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) use ($startTime, $endTime) {
                // Overlap condition
                $q->whereBetween('scheduled_at', [$startTime, $endTime])
                  ->orWhereRaw("DATETIME(scheduled_at, '+' || duration_minutes || ' minutes') > ?", [$startTime->toDateTimeString()]);
            });

        if ($ignoreAppointmentId) {
            $query->where('id', '!=', $ignoreAppointmentId);
        }

        return $query->exists();
    }
}
