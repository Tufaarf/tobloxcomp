<?php

namespace App\Filament\Resources\DetailedServiceResource\Pages;

use App\Filament\Resources\DetailedServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailedService extends EditRecord
{
    protected static string $resource = DetailedServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
