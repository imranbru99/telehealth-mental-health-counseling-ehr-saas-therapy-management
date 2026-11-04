<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PdfGenerationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_invoice_unicode_pdf_receipt_downloads(): void
    {
        $clientUser = User::where('email', 'client@demo.mhcb.test')->firstOrFail();
        $client = Client::where('email', 'client@demo.mhcb.test')->firstOrFail();
        $invoice = Invoice::where('client_id', $client->id)->firstOrFail();

        $response = $this->actingAs($clientUser)->get(route('invoices.pdf', $invoice->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString($invoice->invoice_number, $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_unauthorized_client_cannot_download_other_clients_invoice(): void
    {
        $clientUser = User::where('email', 'client@demo.mhcb.test')->firstOrFail();
        $client = Client::where('email', 'client@demo.mhcb.test')->firstOrFail();

        // An invoice belonging to another client
        $otherInvoice = Invoice::where('client_id', '!=', $client->id)->firstOrFail();

        $response = $this->actingAs($clientUser)->get(route('invoices.pdf', $otherInvoice->id));

        $response->assertStatus(403);
    }
}
