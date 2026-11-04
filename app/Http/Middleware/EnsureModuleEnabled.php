<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\ModuleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    public function __construct(protected ModuleService $moduleService)
    {
    }

    public function handle(Request $request, Closure $next, string $moduleId): Response
    {
        $tenant = null;
        if (app()->has('current_tenant_id')) {
            $tenant = Tenant::find(app('current_tenant_id'));
        } elseif ($request->user() && $request->user()->tenant_id) {
            $tenant = $request->user()->tenant;
        }

        if (!$this->moduleService->isEnabled($moduleId, $tenant)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "The '{$moduleId}' module is currently disabled for this organization.",
                    'module' => $moduleId,
                ], 403);
            }

            return response()->view('errors.module-disabled', [
                'moduleId' => $moduleId,
            ], 403);
        }

        return $next($request);
    }
}
