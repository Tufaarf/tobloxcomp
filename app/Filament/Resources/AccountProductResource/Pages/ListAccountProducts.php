<?php

namespace App\Filament\Resources\AccountProductResource\Pages;

use App\Filament\Resources\AccountProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountProducts extends ListRecords
{
    protected static string $resource = AccountProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
