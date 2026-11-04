<?php

namespace App\Filament\Resources\MessageResource\Pages;

use App\Filament\Resources\MessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMessage extends CreateRecord
{
    protected static string $resource = MessageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sender_id'] = auth()->id();
        $data['tenant_id'] = auth()->user()?->tenant_id ?? (app()->has('current_tenant_id') ? app('current_tenant_id') : 1);
        return $data;
    }
}
