<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSectionResource\Pages;
use App\Models\HeroSection;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ImageUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class HeroSectionResource extends Resource
{
    protected static ?string $model = HeroSection::class;

    protected static ?string $navigationGroup = 'Landing Page';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->label('Title')
                    ->maxLength(255),

                Textarea::make('description')
                    ->required()
                    ->label('Description')
                    ->maxLength(1000),

                TextInput::make('button_text')
                    ->required()
                    ->label('Button Text')
                    ->maxLength(255),

                TextInput::make('button_url')
                    ->required()
                    ->url()
                    ->label('Button URL')
                    ->maxLength(255),

                FileUpload::make('image_url')
                    ->required()
                    ->image()
                    ->label('Hero Image'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('description')->limit(50),
                ImageColumn::make('image_url')->label('Image'),
                TextColumn::make('button_text'),
                TextColumn::make('button_url'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroSections::route('/'),
            'create' => Pages\CreateHeroSection::route('/create'),
            'edit' => Pages\EditHeroSection::route('/{record}/edit'),
        ];
    }
}
