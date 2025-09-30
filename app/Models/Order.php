<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentMethod;
use App\Models\Product;

class Order extends Model
{
    protected $fillable = [
        'wa_number','roblox_username','roblox_user_id',
        'experience_id','experience_name',
        'product_id','robux_amount','pack_size','pack_price',
        'payment_method_id','admin_fee_percent','admin_fee_amount',
        'subtotal','total','status','approved_at','approved_by','meta',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'meta'        => 'array',
    ];

    public function paymentMethod(){ return $this->belongsTo(PaymentMethod::class); }
    public function product(){ return $this->belongsTo(Product::class); }

    /** Hitung subtotal, admin fee, total. */
    public function recalc(): void
    {
        $robux = (int) ($this->robux_amount ?? 0);
        $packs = $this->pack_size > 0 ? (int) ceil($robux / $this->pack_size) : 0;

        $this->subtotal = $packs * (int) $this->pack_price;

        $percent = $this->admin_fee_percent
            ?? optional($this->paymentMethod)->admin_fee_percent
            ?? 0;

        $this->admin_fee_amount = (int) round($this->subtotal * ((float) $percent / 100));
        $this->total = $this->subtotal + $this->admin_fee_amount;
    }
}
