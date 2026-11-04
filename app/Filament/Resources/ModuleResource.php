<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModuleResource\Pages;
use App\Models\Module;
use App\Models\Tenant;
use App\Services\ModuleService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = 'Platform Administration';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('id')
                    ->label('Module ID')
                    ->disabled(),
                Forms\Components\TextInput::make('category')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_core')
                    ->label('Core Module (Cannot be disabled)')
                    ->disabled(),
                Forms\Components\Toggle::make('default_enabled')
                    ->label('Enabled by Default for New Tenants'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\IconColumn::make('is_core')
                    ->label('Core')
                    ->boolean(),
                Tables\Columns\TextColumn::make('min_plan_tier')
                    ->label('Min Plan Tier')
                    ->badge(),
                Tables\Columns\TextColumn::make('dependencies')
                    ->label('Requires')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) && !empty($state) ? implode(', ', $state) : 'None'),
                Tables\Columns\TextColumn::make('version')
                    ->badge()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggleForTenant')
                    ->label('Toggle for Clinic')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn () => auth()->user()?->isOwner() || auth()->user()?->isSuperAdmin())
                    ->action(function (Module $record, ModuleService $moduleService) {
                        $tenant = auth()->user()?->tenant ?? Tenant::first();
                        if (!$tenant) {
                            Notification::make()
                                ->title('No active organization found')
                                ->danger()
                                ->send();
                            return;
                        }

                        $isEnabled = $moduleService->isEnabled($record->id, $tenant);
                        if ($isEnabled) {
                            $res = $moduleService->disableForTenant($tenant, $record->id);
                            if ($res['success']) {
                                Notification::make()
                                    ->title($res['message'])
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title($res['message'])
                                    ->danger()
                                    ->send();
                            }
                        } else {
                            $moduleService->enableForTenant($tenant, $record->id);
                            Notification::make()
                                ->title("Module '{$record->name}' enabled for {$tenant->name}")
                                ->success()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
        ];
    }
}
