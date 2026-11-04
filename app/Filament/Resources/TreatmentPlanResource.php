<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentPlanResource\Pages;
use App\Models\TreatmentPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TreatmentPlanResource extends Resource
{
    protected static ?string $model = TreatmentPlan::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Clinical Practice';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->isSuperAdmin() || $user->isOwner() || $user->isPractitioner();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Treatment Plan Overview')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required(),
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
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'reviewing' => 'Under Review',
                                'completed' => 'Goals Achieved / Completed',
                                'discontinued' => 'Discontinued',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        Forms\Components\DatePicker::make('target_completion_date'),
                        Forms\Components\DatePicker::make('review_date'),
                        Forms\Components\TextInput::make('overall_progress_percentage')
                            ->numeric()
                            ->default(0)
                            ->suffix('%')
                            ->maxValue(100),
                        Forms\Components\Textarea::make('diagnosis_notes')
                            ->label('Clinical Diagnostic Formulation')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Treatment Goals')
                    ->schema([
                        Forms\Components\Repeater::make('goals')
                            ->relationship('goals')
                            ->schema([
                                Forms\Components\TextInput::make('description')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\Select::make('goal_type')
                                    ->options([
                                        'practitioner_assigned' => 'Clinician Objective',
                                        'client_defined' => 'Client Milestone',
                                    ])
                                    ->default('practitioner_assigned'),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'not_started' => 'Not Started',
                                        'in_progress' => 'In Progress',
                                        'achieved' => 'Achieved',
                                        'discontinued' => 'Discontinued',
                                    ])
                                    ->default('in_progress'),
                                Forms\Components\TextInput::make('progress_percentage')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('%'),
                            ])->columns(5)
                            ->columnSpanFull(),
                    ]),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('practitioner.name')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'reviewing' => 'warning',
                        'completed' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('overall_progress_percentage')
                    ->label('Progress')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('review_date')
                    ->date()
                    ->placeholder('N/A'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTreatmentPlans::route('/'),
            'create' => Pages\CreateTreatmentPlan::route('/create'),
            'edit' => Pages\EditTreatmentPlan::route('/{record}/edit'),
        ];
    }
}
