<?php

namespace App\Filament\Resources\AccountProductResource\Pages;

use App\Filament\Resources\AccountProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountProduct extends EditRecord
{
    protected static string $resource = AccountProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
