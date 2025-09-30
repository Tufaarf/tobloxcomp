<?php

namespace App\Filament\Resources\TopupOrderResource\Pages;

use App\Filament\Resources\TopupOrderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTopupOrder extends ViewRecord
{
    protected static string $resource = TopupOrderResource::class;

    protected function getHeaderActions(): array
    {
        return []; // read-only view
    }
}
