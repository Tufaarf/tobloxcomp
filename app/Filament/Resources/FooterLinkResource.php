<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterLinkResource\Pages\CreateFooterLink;
use App\Filament\Resources\FooterLinkResource\Pages\EditFooterLink;
use App\Filament\Resources\FooterLinkResource\Pages\ListFooterLinks;
use App\Models\FooterLink;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class FooterLinkResource extends Resource
{
    protected static ?string $model = FooterLink::class;

        public static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->label('Title')
                    ->maxLength(255),

                TextInput::make('url')
                    ->required()
                    ->url()
                    ->label('URL')
                    ->maxLength(255),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('url')->url(),
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
            'index' => ListFooterLinks::route('/'),
            'create' => CreateFooterLink::route('/create'),
            'edit' => EditFooterLink::route('/{record}/edit'),
        ];
    }
}
