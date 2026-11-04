<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Invoice;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

class PdfService
{
    /**
     * Generate and stream or download an Invoice PDF.
     */
    public function generateInvoicePdf(Invoice $invoice, bool $download = false): Response
    {
        $data = [
            'invoice' => $invoice->load(['client', 'items', 'appointment.service', 'appointment.practitioner', 'tenant']),
        ];

        $filename = "Invoice-{$invoice->invoice_number}.pdf";

        try {
            // Use ImranDev\UnicodePdf package
            $preset = ($invoice->client && $invoice->client->preferred_language === 'bn') ? 'bengali' : 'default';

            $document = UnicodePdf::preset($preset)
                ->loadView('pdf.invoice', $data)
                ->a4();

            if ($download) {
                return $document->download($filename);
            }

            return $document->stream($filename);
        } catch (\Throwable $e) {
            // Graceful fallback to Dompdf if needed
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new Dompdf($options);
            $html = view('pdf.invoice', $data)->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $output = $dompdf->output();

            $disposition = $download ? 'attachment' : 'inline';

            return response($output, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
            ]);
        }
    }
}
