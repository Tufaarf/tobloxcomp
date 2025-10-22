<?php

namespace App\Filament\Resources\RobuxPromoResource\Pages;

use App\Filament\Resources\RobuxPromoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRobuxPromos extends ListRecords
{
    protected static string $resource = RobuxPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
