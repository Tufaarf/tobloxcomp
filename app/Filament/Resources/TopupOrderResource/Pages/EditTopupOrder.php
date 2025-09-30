<?php

namespace App\Filament\Resources\TopupOrderResource\Pages;

use App\Filament\Resources\TopupOrderResource;
use Filament\Resources\Pages\EditRecord;

class EditTopupOrder extends EditRecord
{
    protected static string $resource = TopupOrderResource::class;

    protected function getHeaderActions(): array
    {
        return []; // kita pakai actions di tabel untuk approve/reject/markPaid
    }
}
