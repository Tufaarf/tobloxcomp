<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommunityResource\Pages;
use App\Models\Community;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommunityResource extends Resource
{
    protected static ?string $model = Community::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Community';
    protected static ?string $modelLabel = 'Community';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('header')
                            ->label('Header')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->toolbarButtons([
                                'bold','italic','underline','strike',
                                'blockquote','h2','h3','h4','orderedList','bulletList',
                                'link','redo','undo'
                            ])
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Links')
                    ->schema([
                        Forms\Components\TextInput::make('link_whatsapp')
                            ->label('WhatsApp Link')
                            ->placeholder('https://wa.me/...')
                            ->url()
                            ->prefix('URL'),
                        Forms\Components\TextInput::make('link_instagram')
                            ->label('Instagram Link')
                            ->placeholder('https://instagram.com/...')
                            ->url()
                            ->prefix('URL'),
                        Forms\Components\TextInput::make('link_discord')
                            ->label('Discord Link')
                            ->placeholder('https://discord.gg/...')
                            ->url()
                            ->prefix('URL'),
                        Forms\Components\Toggle::make('is_active')->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('header')->searchable()->limit(40),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommunities::route('/'),
            'create' => Pages\CreateCommunity::route('/create'),
            'edit' => Pages\EditCommunity::route('/{record}/edit'),
        ];
    }
}
