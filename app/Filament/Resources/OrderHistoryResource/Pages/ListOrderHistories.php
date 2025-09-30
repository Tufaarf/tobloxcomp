<?php

namespace App\Filament\Resources\OrderHistoryResource\Pages;

use App\Filament\Resources\OrderHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderHistories extends ListRecords
{
    protected static string $resource = OrderHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
