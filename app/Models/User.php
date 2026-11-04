<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'role',
        'phone',
        'avatar_path',
        'timezone',
        'locale',
        'is_active',
        'two_factor_enabled',
        'two_factor_secret',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function practitionerProfile(): HasOne
    {
        return $this->hasOne(Practitioner::class);
    }

    public function clientProfile(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // Role checks
    public function hasRole(string|array $roles): bool
    {
        $roleList = is_array($roles) ? $roles : func_get_args();

        if (in_array($this->role, $roleList, true)) {
            return true;
        }

        return $this->roles()->where(function ($query) use ($roleList) {
            $query->whereIn('slug', $roleList)->orWhereIn('name', $roleList);
        })->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin' || $this->hasRole('super_admin') || $this->hasRole('Super Administrator');
    }

    public function isPlatformAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    public function isOwner(): bool
    {
        return $this->role === 'organization_owner' || $this->hasRole('organization_owner');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'organization_owner', 'organization_admin', 'clinic_manager'], true);
    }

    public function isPractitioner(): bool
    {
        return in_array($this->role, ['practitioner', 'clinical_director'], true);
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isGuardian(): bool
    {
        return $this->role === 'guardian';
    }

    public function isAuditor(): bool
    {
        return $this->role === 'compliance_auditor';
    }

    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Direct user permission
        if ($this->permissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        // Role permissions
        foreach ($this->roles()->with('permissions')->get() as $role) {
            if ($role->permissions->contains('slug', $permissionSlug)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Filament Panel Authorization
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Active status check
        if (!$this->is_active) {
            return false;
        }

        // Super admins and clinic administrative/clinical staff can access Filament
        return in_array($this->role, [
            'super_admin',
            'platform_support',
            'platform_finance',
            'organization_owner',
            'organization_admin',
            'clinic_manager',
            'clinical_director',
            'practitioner',
            'receptionist',
            'billing_manager',
            'compliance_auditor',
        ], true);
    }
}
