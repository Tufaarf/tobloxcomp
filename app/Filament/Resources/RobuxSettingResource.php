<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RobuxSettingResource\Pages;
use App\Filament\Resources\RobuxSettingResource\RelationManagers;
use App\Models\RobuxSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RobuxSettingResource extends Resource
{
    protected static ?string $model = RobuxSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Harga Robux';
    
    protected static ?string $modelLabel = 'Harga Robux';
    
    protected static ?string $pluralModelLabel = 'Pengaturan Harga Robux';
    
    protected static ?string $navigationGroup = 'Orders';
    
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan Harga Robux')
                    ->description('Tentukan harga per 50 Robux dalam Rupiah')
                    ->schema([
                        Forms\Components\TextInput::make('price_per_50')
                            ->label('Harga per 50 Robux (Rp)')
                            ->numeric()
                            ->required()
                            ->minValue(1000)
                            ->maxValue(100000)
                            ->default(7000)
                            ->prefix('Rp')
                            ->hint('Harga akan otomatis diterapkan di halaman top-up')
                            ->helperText('Masukkan harga dalam Rupiah untuk setiap 50 Robux'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('price_per_50')
                    ->label('Harga per 50 Robux')
                    ->money('IDR')
                    ->sortable()
                    ->description('Harga saat ini yang digunakan di website'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
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
            ])
            ->defaultSort('updated_at', 'desc')
            ->paginated(false); // Only one setting record
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
            'index' => Pages\ListRobuxSettings::route('/'),
            'create' => Pages\CreateRobuxSetting::route('/create'),
            'edit' => Pages\EditRobuxSetting::route('/{record}/edit'),
        ];
    }
}
