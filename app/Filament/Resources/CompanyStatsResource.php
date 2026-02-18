<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyStatsResource\Pages\ListCompanyStats;

use App\Models\CompanyStats;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;

class CompanyStatsResource extends Resource
{
    protected static ?string $model = CompanyStats::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Landing Page';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Judul Statistik')
                    ->required()
                    ->maxLength(255),

                TextInput::make('goals')
                    ->label('Angka')
                    ->required()
                    ->rule('regex:/^[\d.,]+$/'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Statistik')
                    ->searchable(),

                TextColumn::make('goals')
                    ->label('Angka')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Ubah Statistik')
                    ->modalButton('Simpan')
                    ->form([
                        TextInput::make('title')
                            ->label('Judul Statistik')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('goals')
                            ->label('Angka')
                            ->required()
                            ->rule('regex:/^[\d.,]+$/'),
                    ])

                    ->mutateFormDataUsing(fn(array $data) => $data)
                    ->action(fn(CompanyStats $record, array $data) => $record->update($data)),
                DeleteAction::make() // ✅ Delete button
                    ->modalHeading('Hapus Statistik')
                    ->modalButton('Hapus')
                    ->requiresConfirmation()
                    ->action(fn(CompanyStats $record) => $record->delete()),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyStats::route('/'),
        ];
    }
}
