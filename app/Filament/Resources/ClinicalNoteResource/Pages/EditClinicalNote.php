<?php

namespace App\Filament\Resources\ClinicalNoteResource\Pages;

use App\Filament\Resources\ClinicalNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClinicalNote extends EditRecord
{
    protected static string $resource = ClinicalNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
