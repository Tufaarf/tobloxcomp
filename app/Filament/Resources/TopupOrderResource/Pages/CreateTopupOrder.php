<?php

namespace App\Filament\Resources\TopupOrderResource\Pages;

use App\Filament\Resources\TopupOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTopupOrder extends CreateRecord
{
    protected static string $resource = TopupOrderResource::class;
}
