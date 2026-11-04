<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\ClinicalNote;
use App\Models\Tenant;
use App\Models\TherapySession;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cross_tenant_data_isolation_for_clients(): void
    {
        // 1. Fetch Serenity tenant
        $tenantA = Tenant::where('slug', 'serenity-wellness')->firstOrFail();

        // 2. Create Tenant B
        $tenantB = Tenant::create([
            'name' => 'Oakwood Psychological Associates',
            'slug' => 'oakwood-associates',
            'subdomain' => 'oakwood',
            'status' => 'active',
        ]);

        // Create client in Tenant B
        $clientB = Client::create([
            'tenant_id' => $tenantB->id,
            'client_number' => 'CL-OAK-999',
            'first_name' => 'Sophia',
            'last_name' => 'Oakwood',
            'status' => 'active',
        ]);

        // User in Tenant A
        $userA = User::where('email', 'practitioner@demo.mhcb.test')->firstOrFail();

        // Acting as user A with Tenant A active context
        app()->instance('current_tenant_id', $tenantA->id);

        // Querying Client under BelongsToTenant scope
        $clientsForTenantA = Client::all();

        // Assert Tenant A cannot see Tenant B's client
        $this->assertFalse($clientsForTenantA->contains('id', $clientB->id));
        $this->assertNull(Client::find($clientB->id));
    }

    public function test_cross_tenant_data_isolation_for_appointments(): void
    {
        $tenantA = Tenant::where('slug', 'serenity-wellness')->firstOrFail();

        $tenantB = Tenant::create([
            'name' => 'Beacon Behavioral Clinic',
            'slug' => 'beacon-clinic',
            'subdomain' => 'beacon',
            'status' => 'active',
        ]);

        $clientB = Client::create([
            'tenant_id' => $tenantB->id,
            'client_number' => 'CL-BC-001',
            'first_name' => 'Lucas',
            'last_name' => 'Beacon',
            'status' => 'active',
        ]);

        $aptB = Appointment::create([
            'tenant_id' => $tenantB->id,
            'appointment_number' => 'APT-BC-999',
            'client_id' => $clientB->id,
            'practitioner_id' => 1,
            'service_id' => 1,
            'scheduled_at' => now()->addDays(2),
            'duration_minutes' => 50,
            'status' => 'confirmed',
            'fee' => 150.00,
        ]);

        // Set context to Tenant A
        app()->instance('current_tenant_id', $tenantA->id);

        $visibleAppointments = Appointment::all();
        $this->assertFalse($visibleAppointments->contains('id', $aptB->id));
        $this->assertNull(Appointment::find($aptB->id));
    }
}
