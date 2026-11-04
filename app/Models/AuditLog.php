<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create sanitized audit log record preventing sensitive clinical and auth data leaks.
     */
    public static function log(
        string $action,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
        ?int $tenantId = null
    ): self {
        $blacklisted = [
            'password',
            'remember_token',
            'two_factor_secret',
            'card_number',
            'cvv',
            'content', // Clinical note bodies
            'signature_data',
            'api_key',
        ];

        $sanitize = function (array $data) use ($blacklisted) {
            foreach ($blacklisted as $key) {
                if (isset($data[$key])) {
                    $data[$key] = '[REDACTED]';
                }
            }
            return $data;
        };

        return static::create([
            'tenant_id' => $tenantId ?? (app()->has('current_tenant_id') ? app('current_tenant_id') : null),
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? (string) $model->getKey() : null,
            'old_values' => $sanitize($oldValues),
            'new_values' => $sanitize($newValues),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
