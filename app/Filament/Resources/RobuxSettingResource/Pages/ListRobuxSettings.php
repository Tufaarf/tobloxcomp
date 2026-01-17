<?php

namespace App\Filament\Resources\RobuxSettingResource\Pages;

use App\Filament\Resources\RobuxSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRobuxSettings extends ListRecords
{
    protected static string $resource = RobuxSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
