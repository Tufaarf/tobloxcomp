<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountOrderResource\Pages;
use App\Filament\Resources\AccountOrderResource\RelationManagers;
use App\Models\AccountOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountOrderResource extends Resource
{
    protected static ?string $model = AccountOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Details')->schema([
                    Forms\Components\TextInput::make('order_id')
                        ->required()
                        ->readOnly(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'review' => 'Review',
                            'on_progress' => 'On Progress',
                            'delivered' => 'Delivered',
                            'rejected' => 'Rejected',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('payment_method')
                        ->readOnly(),
                    Forms\Components\TextInput::make('total_price')
                        ->numeric()
                        ->prefix('Rp')
                        ->readOnly(),
                    Forms\Components\FileUpload::make('payment_proof')
                        ->image()
                        ->directory('payment-proofs')
                        ->openable()
                        ->downloadable()
                        ->disabled() // Admin only views it, doesn't change it generally
                        ->dehydrated(false), // Ensure it doesnt try to save empty if disabled
                ])->columns(2),

                Forms\Components\Section::make('Customer Info')->schema([
                    Forms\Components\TextInput::make('name')->readOnly(),
                    Forms\Components\TextInput::make('email')->email()->readOnly(),
                    Forms\Components\TextInput::make('phone')->tel()->readOnly(),
                ])->columns(3),

                Forms\Components\Section::make('Product Snapshot')->schema([
                    Forms\Components\TextInput::make('account_name')->label('Account Title')->readOnly(),
                    Forms\Components\TextInput::make('price')->prefix('Rp')->readOnly(),
                    Forms\Components\TextInput::make('game.name')->label('Game')->readOnly(),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('game.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'review' => 'warning',
                        'on_progress' => 'info',
                        'delivered' => 'success',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountOrders::route('/'),
            'create' => Pages\CreateAccountOrder::route('/create'),
            'edit' => Pages\EditAccountOrder::route('/{record}/edit'),
        ];
    }
}
