<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RobuxSetting extends Model
{
    protected $fillable = [
        'price_per_50',
    ];

    protected $casts = [
        'price_per_50' => 'integer',
    ];

    /**
     * Get the current Robux price setting
     */
    public static function getPricePer50(): int
    {
        $setting = self::first();
        
        if (!$setting) {
            // Create default setting if none exists
            $setting = self::create(['price_per_50' => 7000]);
        }
        
        return $setting->price_per_50;
    }
}
