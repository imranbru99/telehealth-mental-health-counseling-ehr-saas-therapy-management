<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'category',
        'description',
        'icon',
        'version',
        'is_core',
        'default_enabled',
        'min_plan_tier',
        'dependencies',
        'settings_schema',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_core' => 'boolean',
            'default_enabled' => 'boolean',
            'dependencies' => 'array',
            'settings_schema' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function tenantModules(): HasMany
    {
        return $this->hasMany(TenantModule::class, 'module_id', 'id');
    }
}
