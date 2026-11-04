<?php

namespace App\Services;

use App\Models\Module;
use App\Models\Tenant;
use App\Models\TenantModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ModuleService
{
    /**
     * Seed or sync modules from code registry into database.
     */
    public function syncRegistry(): void
    {
        $registry = ModuleRegistry::all();

        foreach ($registry as $id => $data) {
            Module::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'category' => $data['category'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                    'version' => $data['version'],
                    'is_core' => $data['is_core'],
                    'default_enabled' => $data['default_enabled'],
                    'min_plan_tier' => $data['min_plan_tier'],
                    'dependencies' => $data['dependencies'],
                    'sort_order' => $data['sort_order'],
                ]
            );
        }

        Cache::forget('system_modules_all');
    }

    /**
     * Get all registered modules.
     */
    public function getAllModules(): Collection
    {
        return Cache::remember('system_modules_all', 3600, function () {
            $modules = Module::orderBy('sort_order')->get();
            if ($modules->isEmpty()) {
                $this->syncRegistry();
                return Module::orderBy('sort_order')->get();
            }
            return $modules;
        });
    }

    /**
     * Check if a module is active for a given tenant.
     */
    public function isEnabled(string $moduleId, ?Tenant $tenant = null): bool
    {
        if (!$tenant) {
            $tenantId = app()->has('current_tenant_id') ? app('current_tenant_id') : null;
            if ($tenantId) {
                $tenant = Tenant::find($tenantId);
            }
        }

        if (!$tenant) {
            // Platform level check: is it in the registry and marked default?
            $mod = $this->getAllModules()->firstWhere('id', $moduleId);
            return $mod ? (bool) $mod->default_enabled : true;
        }

        // Cache per tenant
        $cacheKey = "tenant_{$tenant->id}_module_{$moduleId}";

        return Cache::remember($cacheKey, 600, function () use ($tenant, $moduleId) {
            $tenantModule = TenantModule::where('tenant_id', $tenant->id)
                ->where('module_id', $moduleId)
                ->first();

            if ($tenantModule) {
                return (bool) $tenantModule->is_enabled;
            }

            // Fallback to module default status
            $mod = $this->getAllModules()->firstWhere('id', $moduleId);
            return $mod ? (bool) $mod->default_enabled : true;
        });
    }

    /**
     * Enable a module for a tenant.
     */
    public function enableForTenant(Tenant $tenant, string $moduleId, array $settings = []): bool
    {
        // Check dependencies first
        $missingDeps = $this->getMissingDependencies($tenant, $moduleId);
        if (!empty($missingDeps)) {
            // Auto-enable missing dependencies
            foreach ($missingDeps as $depId) {
                $this->enableForTenant($tenant, $depId);
            }
        }

        TenantModule::updateOrCreate(
            ['tenant_id' => $tenant->id, 'module_id' => $moduleId],
            [
                'is_enabled' => true,
                'settings' => $settings,
            ]
        );

        Cache::forget("tenant_{$tenant->id}_module_{$moduleId}");
        return true;
    }

    /**
     * Disable a module for a tenant.
     */
    public function disableForTenant(Tenant $tenant, string $moduleId): array
    {
        $mod = $this->getAllModules()->firstWhere('id', $moduleId);
        if ($mod && $mod->is_core) {
            return [
                'success' => false,
                'message' => "Module '{$mod->name}' is a core system module and cannot be disabled.",
            ];
        }

        // Check if any other currently enabled modules depend on this one
        $dependents = $this->getActiveDependentModules($tenant, $moduleId);
        if (!empty($dependents)) {
            $names = implode(', ', $dependents);
            return [
                'success' => false,
                'message' => "Cannot disable '{$moduleId}' because the following active modules depend on it: {$names}.",
            ];
        }

        TenantModule::updateOrCreate(
            ['tenant_id' => $tenant->id, 'module_id' => $moduleId],
            ['is_enabled' => false]
        );

        Cache::forget("tenant_{$tenant->id}_module_{$moduleId}");

        return [
            'success' => true,
            'message' => "Module '{$moduleId}' has been deactivated.",
        ];
    }

    /**
     * Get missing dependencies for a tenant wishing to enable a module.
     */
    public function getMissingDependencies(Tenant $tenant, string $moduleId): array
    {
        $mod = $this->getAllModules()->firstWhere('id', $moduleId);
        if (!$mod || empty($mod->dependencies)) {
            return [];
        }

        $missing = [];
        foreach ($mod->dependencies as $depId) {
            if (!$this->isEnabled($depId, $tenant)) {
                $missing[] = $depId;
            }
        }

        return $missing;
    }

    /**
     * Get active modules that depend on this one.
     */
    public function getActiveDependentModules(Tenant $tenant, string $moduleId): array
    {
        $dependents = [];
        foreach ($this->getAllModules() as $mod) {
            if (!empty($mod->dependencies) && in_array($moduleId, $mod->dependencies, true)) {
                if ($this->isEnabled($mod->id, $tenant)) {
                    $dependents[] = $mod->name;
                }
            }
        }
        return $dependents;
    }

    /**
     * Initialize all default modules for a new tenant.
     */
    public function initializeTenantModules(Tenant $tenant): void
    {
        $modules = $this->getAllModules();
        foreach ($modules as $module) {
            TenantModule::updateOrCreate(
                ['tenant_id' => $tenant->id, 'module_id' => $module->id],
                [
                    'is_enabled' => (bool) $module->default_enabled,
                    'settings' => [],
                ]
            );
            Cache::forget("tenant_{$tenant->id}_module_{$module->id}");
        }
    }
}
