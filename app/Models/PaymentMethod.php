<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'bank_name',
        'account_number',
        'account_holder_name',
        'qris_image'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
