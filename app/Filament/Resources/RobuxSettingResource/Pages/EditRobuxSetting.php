<?php

namespace App\Filament\Resources\RobuxSettingResource\Pages;

use App\Filament\Resources\RobuxSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRobuxSetting extends EditRecord
{
    protected static string $resource = RobuxSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
