<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Practitioner;
use App\Models\Service;
use App\Models\Tenant;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BookingEngineTest extends TestCase
{
    use DatabaseTransactions;

    public function test_booking_service_calculates_available_slots(): void
    {
        $tenant = Tenant::where('slug', 'serenity-wellness')->firstOrFail();
        $practitioner = Practitioner::firstOrFail();

        $bookingService = app(BookingService::class);

        // Next Tuesday
        $testDate = Carbon::today()->next(Carbon::TUESDAY)->toDateString();

        $slots = $bookingService->getAvailableSlots($practitioner->id, $testDate);

        $this->assertIsArray($slots);
        $this->assertNotEmpty($slots);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $slots[0]);
    }

    public function test_collision_free_booking_prevents_double_booking(): void
    {
        $tenant = Tenant::where('slug', 'serenity-wellness')->firstOrFail();
        $practitioner = Practitioner::firstOrFail();
        $service = Service::firstOrFail();
        $client1 = Client::firstOrFail();
        $client2 = Client::skip(1)->firstOrFail();

        $bookingService = app(BookingService::class);
        $bookingDate = Carbon::today()->addDays(14)->setTime(10, 0);

        // Book slot for client 1
        $appointment1 = $bookingService->bookAppointment([
            'tenant_id' => $tenant->id,
            'practitioner_id' => $practitioner->id,
            'client_id' => $client1->id,
            'service_id' => $service->id,
            'scheduled_at' => $bookingDate->toDateTimeString(),
            'duration_minutes' => 50,
            'delivery_mode' => 'online_telehealth',
            'fee' => $service->price,
        ]);

        $this->assertNotNull($appointment1);
        $this->assertEquals('confirmed', $appointment1->status);

        // Attempting to book the EXACT same time slot for client 2 must throw an exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('already booked');

        $bookingService->bookAppointment([
            'tenant_id' => $tenant->id,
            'practitioner_id' => $practitioner->id,
            'client_id' => $client2->id,
            'service_id' => $service->id,
            'scheduled_at' => $bookingDate->toDateTimeString(),
            'duration_minutes' => 50,
            'delivery_mode' => 'in_person',
            'fee' => $service->price,
        ]);
    }

    public function test_public_booking_page_renders(): void
    {
        $response = $this->get(route('booking.index'));
        $response->assertStatus(200);
        $response->assertSee('Book a Therapy Session');
    }

    public function test_public_booking_slots_ajax_endpoint(): void
    {
        $practitioner = Practitioner::firstOrFail();
        $service = Service::firstOrFail();
        $testDate = Carbon::today()->next(Carbon::WEDNESDAY)->toDateString();

        $response = $this->getJson(route('booking.slots', [
            'practitioner_id' => $practitioner->id,
            'service_id' => $service->id,
            'date' => $testDate,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['slots']);
    }
}
