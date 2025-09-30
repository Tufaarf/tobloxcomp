<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutResource\Pages;
use App\Models\About;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ImageUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class AboutResource extends Resource
{
    protected static ?string $model = About::class;

    protected static ?string $navigationGroup = 'Landing Page';
    public static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                RichEditor::make('description')
                    ->label('Description')
                    ->required(),

                TextInput::make('headline')
                    ->label('Headline')
                    ->required()
                    ->maxLength(255),

                RichEditor::make('sub_description')
                    ->label('Sub Description')
                    ->required(),

                FileUpload::make('image')
                    ->label('Image')
                    ->required()
                    ->image(),

                FileUpload::make('second_image')
                    ->label('Second Image')
                    ->image()
                    ->required()
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
                TextColumn::make('headline')
                    ->label('Headline')
                    ->limit(50),

                TextColumn::make('sub_description')
                    ->label('Sub Description')
                    ->limit(50),

                ImageColumn::make('image')
                    ->label('Image'),
                ImageColumn::make('second_image')
                    ->label('Second Image'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAbouts::route('/'),
            'create' => Pages\CreateAbout::route('/create'),
            'edit'   => Pages\EditAbout::route('/{record}/edit'),
        ];
    }
}
