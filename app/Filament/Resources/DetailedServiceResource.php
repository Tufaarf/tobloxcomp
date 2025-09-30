<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DetailedServiceResource\Pages;
use App\Models\DetailedService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DetailedServiceResource extends Resource
{
    protected static ?string $model = DetailedService::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Konten';
        public static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Detailed Services';
    protected static ?string $modelLabel = 'Detailed Service';
    protected static ?string $pluralModelLabel = 'Detailed Services';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Layanan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Service')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('detail')
                            ->label('Detail')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'strike',
                                'underline',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'link',
                                'codeBlock',
                                'redo',
                                'undo',
                            ])
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->directory('detailed-services')   // disimpan di storage/app/public/detailed-services
                            ->visibility('public')
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')     // ubah jika perlu
                            ->maxSize(4096),                   // 4 MB
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->square()
                    ->defaultImageUrl(fn () => null),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Service')
                    ->searchable()
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
            'index' => Pages\ListDetailedServices::route('/'),
            'create' => Pages\CreateDetailedService::route('/create'),
            'edit' => Pages\EditDetailedService::route('/{record}/edit'),
        ];
    }
}
