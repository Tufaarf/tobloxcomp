<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopupOrder extends Model
{
    protected $fillable = [
        'order_id',
        'username','roblox_user_id','avatar_url',
        'robux_amount','base_price','tax_rate','tax_amount','total_price',
        'payment_method','pay_to','pay_to_type','wa_number',
        'payment_proof_path','status','meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'tax_rate' => 'float',
    ];

    // === STATUS LIFECYCLE ===
    public const STAT_PENDING     = 'pending';       // default saat dibuat
    public const STAT_APPROVED    = 'approved';      // payment/bukti diterima
    public const STAT_ON_PROGRESS = 'on_progress';   // sedang diproses topup
    public const STAT_COMPLETED   = 'completed';     // topup terkirim
    public const STAT_REJECTED    = 'rejected';      // ditolak (mis. bukti invalid)
    public const STAT_CANCEL      = 'cancelled';     // dibatalkan
}
