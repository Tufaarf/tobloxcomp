<?php

namespace App\Filament\Resources\RobuxPromoResource\Pages;

use App\Filament\Resources\RobuxPromoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRobuxPromo extends EditRecord
{
    protected static string $resource = RobuxPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
