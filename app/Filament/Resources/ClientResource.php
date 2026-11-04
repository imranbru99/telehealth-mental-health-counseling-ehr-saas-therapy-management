<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Client CRM';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Demographics & Profile')
                    ->schema([
                        Forms\Components\TextInput::make('client_number')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('first_name')
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->required(),
                        Forms\Components\DatePicker::make('date_of_birth'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'female' => 'Female',
                                'male' => 'Male',
                                'non_binary' => 'Non-binary',
                                'other' => 'Other / Prefer not to say',
                            ]),
                        Forms\Components\TextInput::make('phone')
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->email(),
                        Forms\Components\Select::make('assigned_practitioner_id')
                            ->label('Primary Practitioner')
                            ->relationship('assignedPractitioner', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Emergency Contact')
                    ->schema([
                        Forms\Components\TextInput::make('emergency_contact_name'),
                        Forms\Components\TextInput::make('emergency_contact_phone')
                            ->tel(),
                        Forms\Components\TextInput::make('emergency_contact_relation')
                            ->placeholder('e.g. Spouse, Parent, Sibling'),
                    ])->columns(3),

                Forms\Components\Section::make('Status & Consent')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'discharged' => 'Discharged',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\Toggle::make('intake_completed')
                            ->label('Intake Form Submitted'),
                        Forms\Components\Toggle::make('consent_signed')
                            ->label('Treatment Consent Signed'),
                        Forms\Components\Textarea::make('crm_notes')
                            ->label('Administrative Notes (Non-Clinical)')
                            ->helperText('Do not enter confidential clinical or diagnostic notes here')
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_number')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Client Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedPractitioner.name')
                    ->label('Practitioner')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\IconColumn::make('intake_completed')
                    ->label('Intake')
                    ->boolean(),
                Tables\Columns\IconColumn::make('consent_signed')
                    ->label('Consent')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'discharged' => 'gray',
                        default => 'slate',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
