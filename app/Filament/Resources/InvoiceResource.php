<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance & Billing';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Header')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('issue_date')
                            ->default(today())
                            ->required(),
                        Forms\Components\DatePicker::make('due_date')
                            ->default(today()->addDays(14))
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent / Outstanding',
                                'partially_paid' => 'Partially Paid',
                                'paid' => 'Fully Paid',
                                'overdue' => 'Overdue',
                                'refunded' => 'Refunded',
                            ])
                            ->default('sent')
                            ->required(),
                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\TextInput::make('paid_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00),
                    ])->columns(2),

                Forms\Components\Section::make('Invoice Line Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\TextInput::make('description')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                Forms\Components\TextInput::make('total_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                            ])->columns(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('issue_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partially_paid' => 'warning',
                        'overdue' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('recordPayment')
                    ->label('Record Payment')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->visible(fn (Invoice $record) => $record->status !== 'paid')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Payment Amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(fn (Invoice $record) => $record->balance_due)
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'stripe' => 'Stripe Card Payment',
                                'sslcommerz' => 'SSLCommerz',
                                'cash' => 'Cash',
                                'check' => 'Check / Bank Transfer',
                                'portal' => 'Client Portal Online',
                            ])
                            ->default('stripe')
                            ->required(),
                    ])
                    ->action(function (Invoice $record, array $data) {
                        $amt = (float) $data['amount'];
                        $newPaid = $record->paid_amount + $amt;
                        $status = ($newPaid >= $record->total_amount) ? 'paid' : 'partially_paid';

                        Payment::create([
                            'tenant_id' => $record->tenant_id,
                            'invoice_id' => $record->id,
                            'client_id' => $record->client_id,
                            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                            'amount' => $amt,
                            'payment_method' => $data['payment_method'],
                            'status' => 'success',
                            'paid_at' => now(),
                        ]);

                        $record->update([
                            'paid_amount' => $newPaid,
                            'status' => $status,
                        ]);

                        Notification::make()
                            ->title('Payment recorded successfully')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('pdfDownload')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->url(fn (Invoice $record) => route('invoices.pdf', $record->id), shouldOpenInNewTab: true),

                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
