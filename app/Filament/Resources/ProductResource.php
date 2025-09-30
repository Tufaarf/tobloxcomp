<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $navigationLabel = 'Products';
    protected static ?string $modelLabel = 'Product';
    protected static ?string $pluralModelLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Product')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('image')
                            ->label('Foto')
                            ->image()
                            ->directory('products')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(4096),

                        Forms\Components\RichEditor::make('detail')
                            ->label('Detail Product')
                            ->toolbarButtons([
                                'bold','italic','strike','underline',
                                'h2','h3','bulletList','orderedList',
                                'blockquote','link','codeBlock','redo','undo',
                            ])
                            ->columnSpanFull(),

                        // Harga Rupiah dengan format otomatis di form
                        Forms\Components\TextInput::make('price')
                        ->label('Harga')
                        ->required()
                        ->numeric()     // input number biasa
                        ->rule('integer')
                        ->minValue(0)
                        ->step(1)
                        ->default(0)
                        ->helperText('Masukkan angka saja, tanpa titik/koma.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                // Tampilkan harga sebagai Rupiah di tabel
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id_ID') // format "Rp12.345"
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
