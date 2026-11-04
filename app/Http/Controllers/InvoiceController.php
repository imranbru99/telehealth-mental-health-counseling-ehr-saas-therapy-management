<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\PdfService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(protected PdfService $pdfService)
    {
    }

    public function downloadPdf(Request $request, Invoice $invoice)
    {
        // Permission check: Superadmin, staff with billing permission, or invoice client
        $user = auth()->user();
        if ($user) {
            $isClientOwner = ($user->clientProfile && $user->clientProfile->id === $invoice->client_id) || ($user->email === $invoice->client->email);
            if (!$isClientOwner && !$user->isAdmin() && !$user->isSuperAdmin()) {
                abort(403, 'Unauthorized access to invoice.');
            }
        }

        $download = $request->boolean('download', false);

        return $this->pdfService->generateInvoicePdf($invoice, $download);
    }
}
