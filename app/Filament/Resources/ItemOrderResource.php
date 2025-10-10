<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemOrderResource\Pages;
use App\Models\ItemOrder;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class ItemOrderResource extends Resource
{
    protected static ?string $model = ItemOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Game Item';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                TextInput::make('wa_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->nullable()
                    ->email(),
                TextInput::make('payment_method')
                    ->required()
                    ->maxLength(255),
                TextInput::make('item_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('game_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('item_price')
                    ->required()
                    ->numeric(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                    ])
                    ->default('pending')
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('username'),
                TextColumn::make('item_name'),
                TextColumn::make('game_name'),
                TextColumn::make('item_price'),
                TextColumn::make('total_price'),
                TextColumn::make('status'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                // Add filters for status
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemOrders::route('/'),
            'create' => Pages\CreateItemOrder::route('/create'),
            'edit' => Pages\EditItemOrder::route('/{record}/edit'),
        ];
    }
}
