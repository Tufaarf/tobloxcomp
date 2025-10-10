<?php

namespace App\Filament\Resources\ItemOrderResource\Pages;

use App\Filament\Resources\ItemOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemOrder extends CreateRecord
{
    protected static string $resource = ItemOrderResource::class;
}
