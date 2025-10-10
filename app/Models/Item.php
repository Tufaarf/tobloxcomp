<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['game_id', 'banner', 'description', 'price', 'name'];

    public function game(){
        return $this->belongsTo(Game::class);
    }
}
