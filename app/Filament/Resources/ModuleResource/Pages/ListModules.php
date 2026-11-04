<?php

namespace App\Filament\Resources\ModuleResource\Pages;

use App\Filament\Resources\ModuleResource;
use App\Services\ModuleService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('syncRegistry')
                ->label('Sync Module Registry')
                ->icon('heroicon-o-arrow-path')
                ->action(function (ModuleService $service) {
                    $service->syncRegistry();
                    \Filament\Notifications\Notification::make()
                        ->title('Modules synchronized with registry')
                        ->success()
                        ->send();
                }),
        ];
    }
}
