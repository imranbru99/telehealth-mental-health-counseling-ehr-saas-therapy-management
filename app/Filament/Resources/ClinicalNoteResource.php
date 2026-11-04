<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClinicalNoteResource\Pages;
use App\Models\ClinicalNote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClinicalNoteResource extends Resource
{
    protected static ?string $model = ClinicalNote::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Clinical Practice';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        // Strict HIPAA / privacy safeguard: receptionist and billing staff cannot see clinical notes
        return $user->isSuperAdmin() || $user->isOwner() || $user->isPractitioner() || $user->isAuditor();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Confidential Clinical Record')
                    ->description('Protected Health Information (PHI) under strict confidentiality')
                    ->schema([
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->full_name} ({$record->client_number})")
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('practitioner_id')
                            ->relationship('practitioner', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('format')
                            ->options([
                                'SOAP' => 'SOAP (Subjective, Objective, Assessment, Plan)',
                                'DAP' => 'DAP (Data, Assessment, Plan)',
                                'BIRP' => 'BIRP (Behavior, Intervention, Response, Plan)',
                                'GIRP' => 'GIRP (Goal, Intervention, Response, Plan)',
                                'PROGRESS' => 'Standard Progress Note',
                                'INITIAL_ASSESSMENT' => 'Initial Assessment / Diagnostic Evaluation',
                                'DISCHARGE_SUMMARY' => 'Discharge & Termination Summary',
                            ])
                            ->default('SOAP')
                            ->required(),
                        Forms\Components\TextInput::make('summary')
                            ->placeholder('Brief non-identifying overview (e.g. Session 4 CBT check-in)')
                            ->required(),
                        Forms\Components\KeyValue::make('content')
                            ->label('Structured Clinical Fields')
                            ->helperText('Key-value clinical note sections (e.g. subjective, objective, assessment, plan)')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Toggle::make('is_signed')
                            ->label('Digitally Signed by Clinician')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_locked')
                            ->label('Locked Record')
                            ->disabled(),
                        Forms\Components\TextInput::make('version')
                            ->numeric()
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('practitioner.name')
                    ->label('Clinician')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('format')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('summary')
                    ->limit(40),
                Tables\Columns\TextColumn::make('version')
                    ->label('Rev')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('is_signed')
                    ->label('Signed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('signed_at')
                    ->dateTime('M j, Y g:i A')
                    ->placeholder('Draft (Unsigned)'),
            ])
            ->actions([
                Tables\Actions\Action::make('signNote')
                    ->label('Sign & Lock')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->visible(fn (ClinicalNote $record) => !$record->is_signed && auth()->user()?->isPractitioner())
                    ->action(function (ClinicalNote $record) {
                        $record->sign(auth()->user());
                        Notification::make()
                            ->title('Clinical Note signed and locked with cryptographic timestamp.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('amendNote')
                    ->label('Amend Note')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning')
                    ->visible(fn (ClinicalNote $record) => $record->is_signed && (auth()->user()?->isPractitioner() || auth()->user()?->isOwner()))
                    ->form([
                        Forms\Components\Textarea::make('amendment_reason')
                            ->label('Mandatory Reason for Amendment')
                            ->required(),
                        Forms\Components\KeyValue::make('new_content')
                            ->label('Updated Content')
                            ->required(),
                    ])
                    ->action(function (ClinicalNote $record, array $data) {
                        $record->amend(auth()->user(), $data['new_content'], $data['amendment_reason']);
                        Notification::make()
                            ->title("Note amended. Version incremented to {$record->version}.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->visible(fn (ClinicalNote $record) => !$record->is_locked),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClinicalNotes::route('/'),
            'create' => Pages\CreateClinicalNote::route('/create'),
            'edit' => Pages\EditClinicalNote::route('/{record}/edit'),
        ];
    }
}
