<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderHistoryResource\Pages;
use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderHistoryResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $navigationLabel = 'Order History';
       public static bool $shouldRegisterNavigation = false;

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form; // tidak dipakai
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('wa_number')->label('WA'),
                Tables\Columns\TextColumn::make('roblox_username'),
                Tables\Columns\TextColumn::make('experience_name')->label('Experience'),
                Tables\Columns\TextColumn::make('robux_amount')->label('Robux'),
                Tables\Columns\TextColumn::make('paymentMethod.name')->label('Metode'),
                Tables\Columns\BadgeColumn::make('status'),
                Tables\Columns\TextColumn::make('total')->money('IDR', true),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i'),
            ])
            ->actions([])        // <<< tidak ada edit/delete
            ->bulkActions([]);   // <<< nonaktif
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderHistories::route('/'),
        ];
    }
}
