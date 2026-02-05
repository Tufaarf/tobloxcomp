<?php

namespace App\Filament\Resources\AccountOrderResource\Pages;

use App\Filament\Resources\AccountOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountOrder extends CreateRecord
{
    protected static string $resource = AccountOrderResource::class;
}
