<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'account_product_id',
        'game_id',
        'account_name',
        'price',
        'name',
        'email',
        'phone',
        'payment_method',
        'total_price',
        'status',
        'payment_proof',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function accountProduct()
    {
        return $this->belongsTo(AccountProduct::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
