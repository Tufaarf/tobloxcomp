<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RobuxPromoResource\Pages;
use App\Filament\Resources\RobuxPromoResource\RelationManagers;
use App\Models\RobuxPromo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// Form Components
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;

// Table Components
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class RobuxPromoResource extends Resource
{
    protected static ?string $model = RobuxPromo::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Promo'; // Grup menu dalam B. Inggris

    protected static ?string $modelLabel = 'Robux Promo';

    protected static ?string $pluralModelLabel = 'Robux Promos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Robux Promo Settings')
                    ->description('Set promo prices based on minimum purchase.')
                    ->schema([
                        TextInput::make('min_purchase_amount')
                            ->label('Minimum Purchase (Robux)')
                            ->required()
                            ->numeric()
                            ->helperText('Minimum Robux amount to get the promo price.'),

                        TextInput::make('promo_price')
                            ->label('Promo Price')
                            ->required()
                            ->numeric()
                            ->prefix('IDR') // Sesuaikan dengan mata uang Anda
                            ->helperText('The total price for the minimum Robux amount specified above.'),

                        TextInput::make('max_purchase_amount')
                            ->label('Maximum Purchase (Robux)')
                            ->required()
                            ->numeric()
                            ->helperText('Maximum Robux amount that can be bought in this promo.'),

                        Toggle::make('is_active')
                            ->label('Activate This Promo')
                            ->required()
                            ->default(false)
                            ->helperText('Only active promos will be shown on the website.'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('min_purchase_amount')
                    ->label('Min. Purchase (Robux)')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('promo_price')
                    ->label('Promo Price')
                    ->money('IDR', true) // Format as currency
                    ->sortable(),

                TextColumn::make('max_purchase_amount')
                    ->label('Max. Purchase (Robux)')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(), // Shows a check/cross icon

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRobuxPromos::route('/'),
            'create' => Pages\CreateRobuxPromo::route('/create'),
            'edit' => Pages\EditRobuxPromo::route('/{record}/edit'),
        ];
    }
}
