<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailedService extends Model
{
    protected $fillable = [
         'name',
        'detail',
        'image',
    ];
}
