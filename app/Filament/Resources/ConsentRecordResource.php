<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsentRecordResource\Pages;
use App\Models\ConsentRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ConsentRecordResource extends Resource
{
    protected static ?string $model = ConsentRecord::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Security & Compliance';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informed Consent Agreement')
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('consent_type')
                            ->placeholder('e.g. treatment_consent, privacy_notice, telehealth_consent')
                            ->required(),
                        Forms\Components\TextInput::make('document_title')
                            ->required(),
                        Forms\Components\TextInput::make('signer_name')
                            ->required(),
                        Forms\Components\DateTimePicker::make('agreed_at')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active / Enforced',
                                'revoked' => 'Revoked by Client/Guardian',
                                'expired' => 'Expired',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\Textarea::make('document_text')
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.full_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('consent_type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('signer_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreed_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'revoked' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ConsentRecord $record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->action(function (ConsentRecord $record) {
                        $record->update([
                            'status' => 'revoked',
                            'revoked_at' => now(),
                        ]);
                        Notification::make()
                            ->title('Consent record revoked.')
                            ->warning()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsentRecords::route('/'),
        ];
    }
}
