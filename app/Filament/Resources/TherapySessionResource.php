<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TherapySessionResource\Pages;
use App\Models\ClinicalNote;
use App\Models\TherapySession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TherapySessionResource extends Resource
{
    protected static ?string $model = TherapySession::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Clinical Practice';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Therapy Session Record')
                    ->schema([
                        Forms\Components\TextInput::make('session_number')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('practitioner_id')
                            ->relationship('practitioner', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('session_type')
                            ->options([
                                'individual' => 'Individual Counseling',
                                'couples' => 'Couples / Marriage Counseling',
                                'family' => 'Family Therapy',
                                'group' => 'Group Therapy',
                                'child_adolescent' => 'Child & Adolescent Counseling',
                                'online' => 'Online Telehealth Session',
                                'in_person' => 'In-Person Session',
                            ])
                            ->default('individual')
                            ->required(),
                        Forms\Components\DateTimePicker::make('started_at'),
                        Forms\Components\DateTimePicker::make('ended_at'),
                        Forms\Components\TextInput::make('duration_minutes')
                            ->numeric()
                            ->default(50)
                            ->suffix('min'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('completed')
                            ->required(),
                        Forms\Components\Select::make('documentation_status')
                            ->options([
                                'pending' => 'Pending Note',
                                'draft' => 'Draft Saved',
                                'signed' => 'Signed by Practitioner',
                                'locked' => 'Locked & Archived',
                            ])
                            ->default('pending'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_number')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('practitioner.name')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('session_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        'scheduled' => 'primary',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('documentation_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'signed' => 'success',
                        'locked' => 'emerald',
                        'draft' => 'warning',
                        default => 'rose',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('createSoapNote')
                    ->label('New SOAP Note')
                    ->icon('heroicon-o-document-plus')
                    ->color('primary')
                    ->action(function (TherapySession $record) {
                        $note = ClinicalNote::create([
                            'tenant_id' => $record->tenant_id,
                            'therapy_session_id' => $record->id,
                            'client_id' => $record->client_id,
                            'practitioner_id' => $record->practitioner_id,
                            'format' => 'SOAP',
                            'summary' => "SOAP documentation for session {$record->session_number}",
                            'content' => [
                                'subjective' => 'Client reports feeling moderate anxiety regarding upcoming life changes.',
                                'objective' => 'Affect congruent with mood; alert, oriented, cooperative throughout the 50-minute session.',
                                'assessment' => 'Client is demonstrating positive response to cognitive reframing interventions.',
                                'plan' => 'Continue weekly individual CBT sessions; practice daily thought record worksheet.',
                            ],
                            'is_signed' => false,
                            'version' => 1,
                        ]);

                        $record->update(['documentation_status' => 'draft']);

                        Notification::make()
                            ->title('SOAP Clinical Note drafted successfully')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTherapySessions::route('/'),
            'create' => Pages\CreateTherapySession::route('/create'),
            'edit' => Pages\EditTherapySession::route('/{record}/edit'),
        ];
    }
}
