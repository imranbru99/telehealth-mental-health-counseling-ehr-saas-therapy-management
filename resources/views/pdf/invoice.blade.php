<!DOCTYPE html>
<html lang="{{ $invoice->client->preferred_language ?? 'en' }}">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Noto Sans', sans-serif;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.5;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #0f766e;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .clinic-name {
            font-size: 22px;
            font-weight: bold;
            color: #0f766e;
        }
        .clinic-tagline {
            font-size: 12px;
            color: #64748b;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #0f766e;
            text-align: right;
        }
        .invoice-number {
            font-size: 13px;
            color: #64748b;
            text-align: right;
        }
        .details-grid {
            width: 100%;
            margin-bottom: 25px;
        }
        .details-grid td {
            vertical-align: top;
            width: 50%;
        }
        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 6px;
        }
        .client-name {
            font-size: 15px;
            font-weight: bold;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table.items-table th {
            background-color: #f1f5f9;
            color: #475569;
            text-align: left;
            padding: 10px 12px;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
        }
        table.items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 12px;
        }
        .total-row {
            font-size: 15px;
            font-weight: bold;
            color: #0f766e;
            border-top: 2px solid #0f766e;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="vertical-align: top;">
                <div class="clinic-name">{{ $invoice->tenant->name ?? 'Serenity Mental Wellness Center' }}</div>
                <div class="clinic-tagline">Licensed Healthcare & Clinical Counseling Practice</div>
                <div style="margin-top: 5px; color: #64748b; font-size: 11px;">
                    Email: contact@serenitywellness.test | Helpline: {{ $invoice->tenant->crisis_hotline ?? '988' }}
                </div>
            </td>
            <td style="text-align: right; vertical-align: top;">
                <div class="invoice-title">INVOICE / চালানপত্র</div>
                <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
                <div style="margin-top: 5px;">
                    <span class="badge">{{ strtoupper($invoice->status) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="details-grid">
        <tr>
            <td>
                <div class="section-title">Billed To / সেবা গ্রহণকারী:</div>
                <div class="client-name">{{ $invoice->client->full_name }}</div>
                <div>Client ID: {{ $invoice->client->client_number }}</div>
                <div>Email: {{ $invoice->client->email }}</div>
                <div>Phone: {{ $invoice->client->phone }}</div>
            </td>
            <td style="text-align: right;">
                <div class="section-title">Invoice Information:</div>
                <div><strong>Issue Date:</strong> {{ $invoice->issue_date->format('F d, Y') }}</div>
                <div><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</div>
                @if($invoice->appointment)
                    <div><strong>Session:</strong> {{ $invoice->appointment->service->name ?? 'Therapy Consultation' }}</div>
                    <div><strong>Practitioner:</strong> {{ $invoice->appointment->practitioner->name ?? 'Clinic Specialist' }}</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 55%;">Description / বিবরণ</th>
                <th style="width: 15%; text-align: center;">Qty</th>
                <th style="width: 15%; text-align: right;">Unit Price</th>
                <th style="width: 15%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->total_price, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td>Professional Therapy Consultation Session (50 min)</td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;">${{ number_format($invoice->total_amount, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td style="color: #64748b;">Subtotal:</td>
            <td style="text-align: right; font-weight: 500;">${{ number_format($invoice->subtotal > 0 ? $invoice->subtotal : $invoice->total_amount, 2) }}</td>
        </tr>
        @if($invoice->tax_amount > 0)
        <tr>
            <td style="color: #64748b;">Tax ({{ $invoice->tax_rate }}%):</td>
            <td style="text-align: right;">${{ number_format($invoice->tax_amount, 2) }}</td>
        </tr>
        @endif
        @if($invoice->discount_amount > 0)
        <tr>
            <td style="color: #64748b;">Discount:</td>
            <td style="text-align: right; color: #dc2626;">-${{ number_format($invoice->discount_amount, 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td>Total Amount:</td>
            <td style="text-align: right;">${{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td style="color: #166534; font-weight: bold;">Amount Paid:</td>
            <td style="text-align: right; color: #166534; font-weight: bold;">${{ number_format($invoice->paid_amount, 2) }}</td>
        </tr>
        @if($invoice->balance_due > 0)
        <tr style="border-top: 1px dashed #cbd5e1; font-weight: bold; color: #b91c1c;">
            <td>Balance Due:</td>
            <td style="text-align: right;">${{ number_format($invoice->balance_due, 2) }}</td>
        </tr>
        @endif
    </table>

    <div class="footer">
        <div>Thank you for choosing {{ $invoice->tenant->name ?? 'Serenity Mental Wellness Center' }} for your mental healthcare journey.</div>
        <div style="margin-top: 4px;">This is a computer-generated billing statement. Non-emergency clinical document. For emergency assistance call 988.</div>
    </div>
</body>
</html>
