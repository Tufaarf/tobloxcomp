<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Payment Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('code', \Illuminate\Support\Str::slug($state))),
                    
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    
                    Forms\Components\Select::make('type')
                        ->options([
                            'transfer' => 'Transfer Bank',
                            'qris' => 'QRIS',
                        ])
                        ->default('transfer')
                        ->required()
                        ->live(),
                ])->columns(2),

            Forms\Components\Section::make('Bank Information')
                ->schema([
                    Forms\Components\TextInput::make('bank_name')
                        ->label('Nama Bank')
                        ->requiredIf('type', 'transfer'),
                    
                    Forms\Components\TextInput::make('account_number')
                        ->label('Nomor Rekening')
                        ->requiredIf('type', 'transfer'),
                        
                    Forms\Components\TextInput::make('account_holder_name')
                        ->label('Atas Nama')
                        ->requiredIf('type', 'transfer'),
                ])
                ->visible(fn (Forms\Get $get) => $get('type') === 'transfer')
                ->columns(3),

            Forms\Components\Section::make('QRIS Information')
                ->schema([
                    Forms\Components\FileUpload::make('qris_image')
                        ->label('Upload QRIS Image')
                        ->image()
                        ->directory('qris-images')
                        ->requiredIf('type', 'qris')
                        ->columnSpanFull(),
                ])
                ->visible(fn (Forms\Get $get) => $get('type') === 'qris'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('code')->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transfer' => 'info',
                        'qris' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
