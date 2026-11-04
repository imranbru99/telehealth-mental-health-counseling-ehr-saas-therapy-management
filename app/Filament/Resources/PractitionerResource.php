<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PractitionerResource\Pages;
use App\Models\Practitioner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PractitionerResource extends Resource
{
    protected static ?string $model = Practitioner::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Clinic Operations';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Practitioner Profile')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->placeholder('e.g. Dr., PsyD, LMFT, LCSW, LPC')
                            ->required(),
                        Forms\Components\TagsInput::make('specialties')
                            ->placeholder('Add specialty (e.g. CBT, Trauma, Anxiety)')
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('languages')
                            ->placeholder('Add language (e.g. English, Bengali)')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('bio')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('base_fee')
                            ->numeric()
                            ->prefix('$')
                            ->default(100.00)
                            ->required(),
                        Forms\Components\TextInput::make('session_duration_minutes')
                            ->numeric()
                            ->default(50)
                            ->suffix('min')
                            ->required(),
                        Forms\Components\TextInput::make('license_number')
                            ->placeholder('State license identifier'),
                        Forms\Components\Toggle::make('license_verified')
                            ->label('License Verified (Credentials Auditor)')
                            ->default(false),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured in Public Directory'),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Practitioner')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('title')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('specialties')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', array_slice($state, 0, 2)) : $state),
                Tables\Columns\TextColumn::make('base_fee')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('license_verified')
                    ->label('Verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPractitioners::route('/'),
            'create' => Pages\CreatePractitioner::route('/create'),
            'edit' => Pages\EditPractitioner::route('/{record}/edit'),
        ];
    }
}
