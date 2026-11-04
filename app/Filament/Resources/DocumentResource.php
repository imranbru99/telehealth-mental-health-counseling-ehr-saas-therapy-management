<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder-lock';
    protected static ?string $navigationGroup = 'Security & Compliance';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Secure Document')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required(),
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->searchable(),
                        Forms\Components\Select::make('category')
                            ->options([
                                'intake' => 'Intake Document',
                                'clinical' => 'Clinical Attachment / Lab',
                                'consent' => 'Signed Consent Record',
                                'billing' => 'Billing Statement / Receipt',
                                'report' => 'Evaluation Report',
                                'general' => 'General Practice Document',
                            ])
                            ->default('general')
                            ->required(),
                        Forms\Components\Select::make('access_level')
                            ->options([
                                'clinical_only' => 'Clinical Only (Clinicians & Directors)',
                                'staff_accessible' => 'Staff Accessible (Administrative)',
                                'client_accessible' => 'Client Portal Accessible',
                            ])
                            ->default('staff_accessible')
                            ->required(),
                        Forms\Components\TextInput::make('file_path')
                            ->label('Storage Path')
                            ->required(),
                        Forms\Components\TextInput::make('file_type')
                            ->default('application/pdf'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->placeholder('Organization-Wide'),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('access_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'clinical_only' => 'danger',
                        'client_accessible' => 'success',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
