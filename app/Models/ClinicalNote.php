<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalNote extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'therapy_session_id',
        'client_id',
        'practitioner_id',
        'format',
        'summary',
        'content',
        'is_signed',
        'signed_at',
        'signed_by_id',
        'signature_hash',
        'is_locked',
        'version',
        'parent_note_id',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_signed' => 'boolean',
            'signed_at' => 'datetime',
            'is_locked' => 'boolean',
            'version' => 'integer',
        ];
    }

    public function therapySession(): BelongsTo
    {
        return $this->belongsTo(TherapySession::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_id');
    }

    public function parentNote(): BelongsTo
    {
        return $this->belongsTo(ClinicalNote::class, 'parent_note_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ClinicalNoteVersion::class)->orderByDesc('version_number');
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(ClinicalNoteAccessLog::class)->orderByDesc('created_at');
    }

    /**
     * Sign and lock this clinical note
     */
    public function sign(User $user, string $signatureData = ''): void
    {
        $hash = hash('sha256', json_encode($this->content) . $user->id . now()->toIso8601String() . $signatureData);

        $this->update([
            'is_signed' => true,
            'signed_at' => now(),
            'signed_by_id' => $user->id,
            'signature_hash' => $hash,
            'is_locked' => true,
        ]);

        if ($this->therapySession) {
            $this->therapySession->update(['documentation_status' => 'signed']);
        }

        $this->logAccess($user, 'sign');
    }

    /**
     * Create an auditable amendment for a signed note
     */
    public function amend(User $user, array $newContent, string $reason): void
    {
        // 1. Archive current version
        ClinicalNoteVersion::create([
            'clinical_note_id' => $this->id,
            'version_number' => $this->version,
            'content' => $this->content,
            'amendment_reason' => $reason,
            'amended_by_id' => $user->id,
        ]);

        // 2. Update note with new content and increment version
        $this->update([
            'content' => $newContent,
            'version' => $this->version + 1,
            'is_signed' => false,
            'signed_at' => null,
            'signed_by_id' => null,
            'signature_hash' => null,
            'is_locked' => false,
        ]);

        $this->logAccess($user, 'edit');
    }

    /**
     * Log user access to confidential clinical documentation
     */
    public function logAccess(User $user, string $action): void
    {
        ClinicalNoteAccessLog::create([
            'tenant_id' => $this->tenant_id,
            'clinical_note_id' => $this->id,
            'user_id' => $user->id,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
