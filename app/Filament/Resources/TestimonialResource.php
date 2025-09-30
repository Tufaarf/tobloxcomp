<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages\CreateTestimonial;
use App\Filament\Resources\TestimonialResource\Pages\EditTestimonial;
use App\Filament\Resources\TestimonialResource\Pages\ListTestimonials;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ImageUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

        public static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Name')
                    ->maxLength(255),

                TextInput::make('position')
                    ->required()
                    ->label('Position')
                    ->maxLength(100),

                Textarea::make('testimonial')
                    ->required()
                    ->label('Testimonial')
                    ->maxLength(1000),

                TextInput::make('stars')
                    ->required()
                    ->label('Stars (Rating)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5),

                FileUpload::make('image_url')
                    ->nullable()
                    ->image()
                    ->label('Image'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('position'),
                TextColumn::make('testimonial')->limit(50),
                TextColumn::make('stars')->sortable(),
                ImageColumn::make('image_url')->label('Image'),
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
            'index' => ListTestimonials::route('/'),
            'create' => CreateTestimonial::route('/create'),
            'edit' => EditTestimonial::route('/{record}/edit'),
        ];
    }
}
