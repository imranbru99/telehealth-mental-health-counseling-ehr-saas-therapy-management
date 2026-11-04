<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Practitioner extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'title',
        'specialties',
        'languages',
        'qualifications',
        'license_number',
        'license_verified',
        'license_verified_at',
        'bio',
        'base_fee',
        'session_duration_minutes',
        'rating',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'specialties' => 'array',
            'languages' => 'array',
            'license_verified' => 'boolean',
            'license_verified_at' => 'datetime',
            'base_fee' => 'float',
            'session_duration_minutes' => 'integer',
            'rating' => 'float',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'practitioner_branches');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(PractitionerAvailability::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(PractitionerLeave::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function therapySessions(): HasMany
    {
        return $this->hasMany(TherapySession::class);
    }

    public function clinicalNotes(): HasMany
    {
        return $this->hasMany(ClinicalNote::class);
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getNameAttribute(): string
    {
        $prefix = $this->title ? $this->title . ' ' : '';
        return $prefix . ($this->user ? $this->user->name : 'Practitioner #' . $this->id);
    }
}
