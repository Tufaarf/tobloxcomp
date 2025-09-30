<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopupOrderResource\Pages;
use App\Models\TopupOrder;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter; // <-- Tambahan
use Illuminate\Support\Facades\Storage;

class TopupOrderResource extends Resource
{
    protected static ?string $model = TopupOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Orders';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order')
                ->schema([
                    Forms\Components\TextInput::make('order_id')->label('Order ID')->disabled(),
                    Forms\Components\TextInput::make('username')->disabled(),
                    Forms\Components\TextInput::make('roblox_user_id')->label('Roblox ID')->disabled(),
                    Forms\Components\TextInput::make('wa_number')->label('WhatsApp')->disabled(),
                    Forms\Components\TextInput::make('payment_method')->label('Payment Method')->disabled(),
                    Forms\Components\TextInput::make('pay_to')->label('Tujuan')->disabled(),
                    Forms\Components\TextInput::make('pay_to_type')->label('Tipe Tujuan')->disabled(),
                    Forms\Components\FileUpload::make('payment_proof_path')
                        ->label('Bukti Pembayaran')
                        ->disk('public')->directory('payment_proofs')->imagePreviewHeight('250')->openable()
                        ->downloadable()->disabled(),
                ])->columns(3),

            Forms\Components\Section::make('Pricing')
                ->schema([
                    Forms\Components\TextInput::make('robux_amount')->numeric()->disabled(),
                    Forms\Components\TextInput::make('base_price')->prefix('Rp')->numeric()->disabled(),
                    Forms\Components\TextInput::make('tax_rate')->suffix('%')->numeric()->disabled(),
                    Forms\Components\TextInput::make('tax_amount')->prefix('Rp')->numeric()->disabled(),
                    Forms\Components\TextInput::make('total_price')->prefix('Rp')->numeric()->disabled(),
                    Forms\Components\TextInput::make('status')->disabled(),
                ])->columns(6),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
{
    return $table
        ->defaultSort('id', 'desc')
        ->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('order_id')->label('Order ID')->searchable()->copyable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
            Tables\Columns\TextColumn::make('username')->searchable(),
            Tables\Columns\TextColumn::make('robux_amount')->label('Robux')->sortable(),
            Tables\Columns\TextColumn::make('payment_method')->label('Method'),
            Tables\Columns\TextColumn::make('pay_to')
                ->label('Tujuan')
                ->limit(24)
                ->tooltip(fn (string $state): ?string => $state),
            Tables\Columns\TextColumn::make('total_price')->money('IDR', true)->label('Total'),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->colors([
                    'warning' => TopupOrder::STAT_PENDING,
                    'primary' => TopupOrder::STAT_APPROVED,
                    'info'    => TopupOrder::STAT_ON_PROGRESS,
                    'success' => TopupOrder::STAT_COMPLETED,
                    'danger'  => TopupOrder::STAT_REJECTED,
                ]),
            Tables\Columns\TextColumn::make('payment_proof_path')
                ->label('Proof')
                ->formatStateUsing(fn($state)=> $state ? 'Lihat' : '-')
                ->url(fn($record)=> $record->payment_proof_path ? Storage::disk('public')->url($record->payment_proof_path) : null, true),
        ])
        ->filters([
            \Filament\Tables\Filters\SelectFilter::make('status')
                ->label('Status')         // <-- single select (default)
                ->options([
                    TopupOrder::STAT_PENDING     => 'Pending',
                    TopupOrder::STAT_APPROVED    => 'Approved',
                    TopupOrder::STAT_ON_PROGRESS => 'On Progress',
                    TopupOrder::STAT_COMPLETED   => 'Completed',
                    TopupOrder::STAT_REJECTED    => 'Rejected',
                    // TopupOrder::STAT_CANCEL      => 'Cancelled',
                ])
                ->indicator('Status'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),

            Tables\Actions\Action::make('approve')
                ->label('Approve Payment')
                ->visible(fn(TopupOrder $r) => $r->status === TopupOrder::STAT_PENDING)
                ->color('success')
                ->requiresConfirmation()
                ->action(fn(TopupOrder $r) => $r->update(['status' => TopupOrder::STAT_APPROVED])),

            Tables\Actions\Action::make('reject')
                ->visible(fn(TopupOrder $r)=> $r->status === TopupOrder::STAT_PENDING)
                ->color('danger')
                ->requiresConfirmation()
                ->form([ Forms\Components\Textarea::make('reason')->label('Alasan')->required() ])
                ->action(function (TopupOrder $r, array $data) {
                    $meta = $r->meta ?? []; $meta['reject_reason'] = $data['reason'];
                    $r->update(['status' => TopupOrder::STAT_REJECTED, 'meta' => $meta]);
                }),

            Tables\Actions\Action::make('startProgress')
                ->label('Start Progress')
                ->visible(fn(TopupOrder $r)=> $r->status === TopupOrder::STAT_APPROVED)
                ->color('info')
                ->requiresConfirmation()
                ->action(fn(TopupOrder $r) => $r->update(['status' => TopupOrder::STAT_ON_PROGRESS])),

            Tables\Actions\Action::make('complete')
                ->label('Mark Completed')
                ->visible(fn(TopupOrder $r)=> $r->status === TopupOrder::STAT_ON_PROGRESS)
                ->color('success')
                ->requiresConfirmation()
                ->action(fn(TopupOrder $r) => $r->update(['status' => TopupOrder::STAT_COMPLETED])),
        ])
        ->persistFiltersInSession();
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopupOrders::route('/'),
            'edit'  => Pages\EditTopupOrder::route('/{record}/edit'),
        ];
    }
}
