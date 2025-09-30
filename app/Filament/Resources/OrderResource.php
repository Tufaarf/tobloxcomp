<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Services\RobloxServices;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Notifications\Notification; // << ganti notify() ke Notification
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Orders';
       public static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Create / Manage Orders';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Customer & Roblox')
                ->schema([
                    Forms\Components\TextInput::make('wa_number')
                        ->label('WhatsApp Number')->required()->tel(),

                    Forms\Components\TextInput::make('roblox_username')
                        ->label('Roblox Username')->required()
                        ->live(debounce: 800),

                    Forms\Components\Hidden::make('roblox_user_id'),
                    Forms\Components\Hidden::make('experience_options')->dehydrated(false),

                    // Tombol cek username -> userId
                    Forms\Components\Actions::make([
                        FormAction::make('cekUsername')
                            ->label('Cek Username Roblox')
                            ->action(function (Get $get, Set $set) {
                                $svc   = app(RobloxServices::class);
                                $uname = (string) $get('roblox_username');

                                if (trim($uname) === '') {
                                    Notification::make()
                                        ->title('Isi dulu username Roblox.')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                $userId = $svc->resolveUserId($uname);
                                if ($userId) {
                                    $set('roblox_user_id', $userId);

                                    $list    = $svc->universesByUser($userId);
                                    $options = collect($list)->pluck('name', 'id')->toArray();
                                    $set('experience_options', $options);

                                    Notification::make()
                                        ->title("User ID ditemukan: {$userId}")
                                        ->success()
                                        ->send();
                                } else {
                                    $set('roblox_user_id', null);
                                    $set('experience_options', []);
                                    Notification::make()
                                        ->title('Username tidak ditemukan / tidak valid.')
                                        ->warning()
                                        ->send();
                                }
                            })
                            ->color('primary')
                            ->icon('heroicon-o-magnifying-glass'),
                    ])->alignCenter(),
                ])->columns(3),

            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('experience_id')
                    ->label('Experience / Game')
                    ->options(fn(Get $get) => $get('experience_options') ?? [])
                    ->searchable()
                    ->native(false)
                    ->reactive()
                    ->afterStateUpdated(function($state, Set $set, Get $get){
                        $opts = $get('experience_options') ?? [];
                        $set('experience_name', $state && isset($opts[$state]) ? $opts[$state] : null);
                    })
                    ->hint('Klik "Cek Username Roblox" untuk memuat daftar'),
                Forms\Components\Hidden::make('experience_name'),
            ]),

            Forms\Components\Section::make('Product & Robux')
                ->schema([
                    Forms\Components\Select::make('product_id')
                        ->label('Product')
                        ->options(Product::query()->pluck('name','id'))
                        ->searchable()->native(false),

                    // Ganti slider -> TextInput number + tombol cepat
                    Forms\Components\TextInput::make('robux_amount')
                        ->label('Jumlah Robux')
                        ->type('number')
                        ->minValue(0)
                        ->step(50)               // step 50 agar gampang
                        ->default(0)
                        ->suffix('Robux')
                        ->live()
                        ->afterStateUpdated(fn($state, Set $set, Get $get) => static::recalc($set, $get)),

                    Forms\Components\Actions::make([
                        FormAction::make('+50')->action(fn(Get $get, Set $set) => static::bumpRobux(50,  $get, $set)),
                        FormAction::make('+100')->action(fn(Get $get, Set $set) => static::bumpRobux(100, $get, $set)),
                        FormAction::make('+500')->action(fn(Get $get, Set $set) => static::bumpRobux(500, $get, $set)),
                        FormAction::make('Reset')->color('danger')
                            ->action(function (Get $get, Set $set) {
                                $set('robux_amount', 0);
                                static::recalc($set, $get);
                            }),
                    ])->alignLeft(),

                    Forms\Components\TextInput::make('pack_size')->numeric()->default(50)->suffix('per pack')
                        ->live()->afterStateUpdated(fn($s, Set $set, Get $get) => static::recalc($set,$get)),
                    Forms\Components\TextInput::make('pack_price')->numeric()->default(14000)->prefix('Rp')
                        ->live()->afterStateUpdated(fn($s, Set $set, Get $get) => static::recalc($set,$get)),
                ])->columns(2),

            Forms\Components\Section::make('Payment')
                ->schema([
                    Forms\Components\Select::make('payment_method_id')
                        ->label('Metode Pembayaran')
                        ->options(\App\Models\PaymentMethod::query()->pluck('name','id'))
                        ->searchable()->native(false)
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn($state, Set $set, Get $get) => static::recalc($set, $get)),

                    Forms\Components\TextInput::make('admin_fee_percent')
                        ->label('Admin Fee (%)')
                        ->numeric()->minValue(0)->maxValue(100)->step(0.01)
                        ->hint('Kosongkan untuk pakai default dari metode pembayaran')
                        ->live()
                        ->afterStateUpdated(fn($state, Set $set, Get $get) => static::recalc($set, $get)),

                    Forms\Components\TextInput::make('status')
                        ->default('pending')
                        ->disabled(),
                ])->columns(3),

            Forms\Components\Section::make('Total')
                ->schema([
                    Forms\Components\TextInput::make('subtotal')->prefix('Rp')->disabled(),
                    Forms\Components\TextInput::make('admin_fee_amount')->label('Admin Fee Amount')->prefix('Rp')->disabled(),
                    Forms\Components\TextInput::make('total')->prefix('Rp')->disabled()
                        ->extraAttributes(['class' => 'text-success fw-bold']),
                ])->columns(3),
        ]);
    }

    /** Tombol +50/+100/+500 */
    protected static function bumpRobux(int $delta, Get $get, Set $set): void
    {
        $current = (int) ($get('robux_amount') ?? 0);
        $set('robux_amount', max(0, $current + $delta));
        static::recalc($set, $get);
    }

    /** Hitung subtotal/adminFee/total untuk preview form */
    protected static function recalc(Set $set, Get $get): void
    {
        $order = new \App\Models\Order($get());

        if ($pmId = $get('payment_method_id')) {
            $pm = \App\Models\PaymentMethod::find($pmId);
            if ($pm && is_null($order->admin_fee_percent)) {
                $order->admin_fee_percent = $pm->admin_fee_percent;
            }
        }

        $order->recalc();
        $set('subtotal', $order->subtotal);
        $set('admin_fee_amount', $order->admin_fee_amount);
        $set('total', $order->total);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('wa_number')->label('WA'),
                Tables\Columns\TextColumn::make('roblox_username')->label('Username'),
                Tables\Columns\TextColumn::make('robux_amount')->label('Robux')->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')->label('Metode'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                        'info'    => 'paid',
                        'gray'    => 'canceled',
                    ]),
                Tables\Columns\TextColumn::make('total')->money('IDR', true),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn(Order $r) => $r->status === 'pending'),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')->color('success')->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->visible(fn(Order $r) => $r->status === 'pending')
                    ->action(function (Order $record) {
                        $record->status = 'approved';
                        $record->approved_at = now();
                        $record->approved_by = auth()->id();
                        $record->save();

                        Notification::make()
                            ->title("Order #{$record->id} approved")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')->color('danger')->icon('heroicon-o-x-mark')
                    ->requiresConfirmation()
                    ->visible(fn(Order $r) => $r->status === 'pending')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title("Order #{$record->id} rejected")
                            ->danger()
                            ->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
