<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\TherapySession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Appointments & Booking';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Appointment Details')
                    ->schema([
                        Forms\Components\TextInput::make('appointment_number')
                            ->disabled()
                            ->dehydrated(false),
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
                        Forms\Components\Select::make('service_id')
                            ->relationship('service', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->required(),
                        Forms\Components\TextInput::make('duration_minutes')
                            ->numeric()
                            ->default(50)
                            ->suffix('min')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending Approval',
                                'confirmed' => 'Confirmed',
                                'checked_in' => 'Checked In (Arrived)',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'no_show' => 'No Show',
                            ])
                            ->default('confirmed')
                            ->required(),
                        Forms\Components\Select::make('delivery_mode')
                            ->options([
                                'in_person' => 'In-Person (Clinic)',
                                'online_telehealth' => 'Online Telehealth (Video)',
                            ])
                            ->default('in_person')
                            ->required(),
                        Forms\Components\TextInput::make('fee')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'partially_paid' => 'Partially Paid',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->default('unpaid'),
                        Forms\Components\TextInput::make('telehealth_meeting_url')
                            ->placeholder('Auto-generated if online telehealth')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment_number')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('practitioner.name')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('service.name'),
                Tables\Columns\TextColumn::make('delivery_mode')
                    ->badge()
                    ->color(fn ($state) => $state === 'online_telehealth' ? 'info' : 'gray'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'checked_in' => 'primary',
                        'in_progress' => 'warning',
                        'completed' => 'emerald',
                        'cancelled' => 'danger',
                        'no_show' => 'rose',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn ($state) => $state === 'paid' ? 'success' : 'warning'),
            ])
            ->actions([
                Tables\Actions\Action::make('checkIn')
                    ->label('Check In')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Appointment $record) => in_array($record->status, ['pending', 'confirmed']))
                    ->action(fn (Appointment $record) => $record->update(['status' => 'checked_in'])),

                Tables\Actions\Action::make('startSession')
                    ->label('Start Session')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn (Appointment $record) => in_array($record->status, ['confirmed', 'checked_in']))
                    ->action(function (Appointment $record) {
                        $record->update(['status' => 'in_progress']);

                        TherapySession::firstOrCreate(
                            ['appointment_id' => $record->id],
                            [
                                'tenant_id' => $record->tenant_id,
                                'client_id' => $record->client_id,
                                'practitioner_id' => $record->practitioner_id,
                                'started_at' => now(),
                                'duration_minutes' => $record->duration_minutes,
                                'session_type' => $record->delivery_mode === 'online_telehealth' ? 'online' : 'in_person',
                                'status' => 'in_progress',
                                'documentation_status' => 'pending',
                            ]
                        );

                        Notification::make()
                            ->title('Therapy session started!')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('joinTelehealth')
                    ->label('Join Telehealth')
                    ->icon('heroicon-o-video-camera')
                    ->color('info')
                    ->visible(fn (Appointment $record) => $record->delivery_mode === 'online_telehealth' && !empty($record->telehealth_meeting_url))
                    ->url(fn (Appointment $record) => $record->telehealth_meeting_url, shouldOpenInNewTab: true),

                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
