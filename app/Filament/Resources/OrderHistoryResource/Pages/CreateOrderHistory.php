<?php

namespace App\Filament\Resources\OrderHistoryResource\Pages;

use App\Filament\Resources\OrderHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderHistory extends CreateRecord
{
    protected static string $resource = OrderHistoryResource::class;
}
