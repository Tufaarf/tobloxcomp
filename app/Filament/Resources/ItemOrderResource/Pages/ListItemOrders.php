<?php

namespace App\Filament\Resources\ItemOrderResource\Pages;

use App\Filament\Resources\ItemOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemOrders extends ListRecords
{
    protected static string $resource = ItemOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
