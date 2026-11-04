<?php

namespace App\Filament\Resources\TherapySessionResource\Pages;

use App\Filament\Resources\TherapySessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTherapySession extends EditRecord
{
    protected static string $resource = TherapySessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
