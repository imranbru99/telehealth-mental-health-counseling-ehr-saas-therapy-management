<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Services\ModuleRegistry;
use App\Services\ModuleService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ModuleManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_all_26_saas_modules_registered(): void
    {
        $registry = ModuleRegistry::all();
        $this->assertCount(26, $registry);

        // Spot-check key behavioral health modules
        $this->assertArrayHasKey('appointments_booking', $registry);
        $this->assertArrayHasKey('clinical_notes', $registry);
        $this->assertArrayHasKey('telehealth', $registry);
        $this->assertArrayHasKey('treatment_plans', $registry);
        $this->assertArrayHasKey('attendance_management', $registry);
        $this->assertArrayHasKey('client_portal', $registry);
    }

    public function test_core_module_cannot_be_disabled(): void
    {
        $tenant = Tenant::where('slug', 'serenity-wellness')->firstOrFail();
        $moduleService = app(ModuleService::class);

        // 'appointments_booking' is a core module
        $result = $moduleService->disableForTenant($tenant, 'appointments_booking');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('core system module', $result['message']);
        $this->assertTrue($moduleService->isEnabled('appointments_booking', $tenant));
    }

    public function test_optional_module_can_be_disabled_and_enabled(): void
    {
        $tenant = Tenant::where('slug', 'serenity-wellness')->firstOrFail();
        $moduleService = app(ModuleService::class);

        // 'support_tickets' is an optional module
        $result = $moduleService->disableForTenant($tenant, 'support_tickets');
        $this->assertTrue($result['success']);
        $this->assertFalse($moduleService->isEnabled('support_tickets', $tenant));

        // Re-enable
        $enableResult = $moduleService->enableForTenant($tenant, 'support_tickets');
        $this->assertTrue($enableResult);
        $this->assertTrue($moduleService->isEnabled('support_tickets', $tenant));
    }

    public function test_public_modules_catalog_page_renders(): void
    {
        $response = $this->get(route('modules'));
        $response->assertStatus(200);
        $response->assertSee('26 SaaS Business Modules');
    }
}
