<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Schema::hasTable('tenants')) {
            return $next($request);
        }

        $tenantId = null;

        // 1. Authenticated user's assigned tenant
        if ($request->user() && $request->user()->tenant_id) {
            $tenantId = $request->user()->tenant_id;
        }

        // 2. Custom header for API requests
        if (!$tenantId && $request->header('X-Tenant-ID')) {
            $tenantHeader = $request->header('X-Tenant-ID');
            $tenant = Tenant::where('id', $tenantHeader)
                ->orWhere('uuid', $tenantHeader)
                ->orWhere('slug', $tenantHeader)
                ->first();
            if ($tenant) {
                $tenantId = $tenant->id;
            }
        }

        // 3. Subdomain / Host detection
        if (!$tenantId) {
            $host = $request->getHost();
            $tenant = Tenant::where('custom_domain', $host)
                ->orWhere('subdomain', explode('.', $host)[0])
                ->first();
            if ($tenant) {
                $tenantId = $tenant->id;
            }
        }

        // 4. Session fallback for public booking or guest actions
        if (!$tenantId && session()->has('active_tenant_id')) {
            $tenantId = session('active_tenant_id');
        }

        if ($tenantId) {
            app()->instance('current_tenant_id', $tenantId);
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                if ($tenant->timezone) {
                    config(['app.timezone' => $tenant->timezone]);
                }
            }
        }

        return $next($request);
    }
}
