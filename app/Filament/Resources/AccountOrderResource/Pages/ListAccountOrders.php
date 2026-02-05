<?php

namespace App\Filament\Resources\AccountOrderResource\Pages;

use App\Filament\Resources\AccountOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountOrders extends ListRecords
{
    protected static string $resource = AccountOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
