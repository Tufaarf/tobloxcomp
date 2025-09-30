<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name','admin_fee_percent'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
