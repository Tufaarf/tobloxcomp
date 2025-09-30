<?php

namespace App\Filament\Resources\CompanyStatsResource\Pages;

use App\Filament\Resources\CompanyStatsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyStats extends ListRecords
{
    protected static string $resource = CompanyStatsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
