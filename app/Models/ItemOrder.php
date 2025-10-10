<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'wa_number',
        'email',
        'payment_method',
        'item_id', // ID item yang dipesan
        'game_id', // ID game terkait
        'item_name',
        'game_name',
        'item_price',
        'total_price',
        'status',
    ];

    // Relasi dengan model Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi dengan model Game
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
