<?php

namespace Tests\Feature;

use App\Models\Practitioner;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_api_auth_login_returns_sanctum_token(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'client@demo.mhcb.test',
            'password' => 'Demo@12345',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'token',
            'user' => ['id', 'name', 'email', 'role'],
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('token'));
    }

    public function test_api_practitioners_endpoint(): void
    {
        $response = $this->getJson('/api/v1/practitioners');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'title', 'specialties', 'languages', 'base_fee'],
            ],
        ]);
        $this->assertTrue($response->json('success'));
    }

    public function test_api_services_endpoint(): void
    {
        $response = $this->getJson('/api/v1/services');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'name', 'slug', 'duration_minutes', 'price', 'delivery_mode'],
            ],
        ]);
        $this->assertTrue($response->json('success'));
    }

    public function test_api_branches_endpoint(): void
    {
        $response = $this->getJson('/api/v1/branches');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'name', 'code', 'city', 'state'],
            ],
        ]);
        $this->assertTrue($response->json('success'));
    }

    public function test_api_slots_endpoint(): void
    {
        $practitioner = Practitioner::firstOrFail();
        $testDate = Carbon::today()->next(Carbon::THURSDAY)->toDateString();

        $response = $this->getJson("/api/v1/appointments/slots?practitioner_id={$practitioner->id}&date={$testDate}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'date',
            'slots',
        ]);
        $this->assertTrue($response->json('success'));
    }

    public function test_api_modules_endpoint(): void
    {
        $response = $this->getJson('/api/v1/modules');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertCount(26, $response->json('data'));
    }
}
