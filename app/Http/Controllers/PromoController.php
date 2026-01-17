<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\RobuxPromo; // added import

class PromoController extends Controller
{
   public function index(){


    $paymentMethods = \App\Models\PaymentMethod::all()
            ->map(function ($pm) {
                // Determine target based on type
                $target = $pm->account_number . ($pm->account_holder_name ? ' (' . $pm->account_holder_name . ')' : '');
                $type   = 'text';

                if ($pm->type === 'qris') {
                    $type = 'image';
                    $target = $pm->qris_image ? \Illuminate\Support\Facades\Storage::url($pm->qris_image) : asset('images/qris-placeholder.png');
                }

                return [
                    'code'   => $pm->code,
                    'name'   => $pm->name,
                    'fee'    => 0,
                    'type'   => $type,
                    'target' => $target,
                ];
            })->values();

    // Ambil data RobuxPromo dengan kolom persis seperti di Filament Resource
    $robuxPromos = RobuxPromo::select([
        'min_purchase_amount',
        'promo_price',
        'max_purchase_amount',
        'is_active',
        'created_at',
    ])->get();

    // Cari promo aktif terbaru; jika tidak ada gunakan fallback
    $activePromo = RobuxPromo::where('is_active', true)->orderByDesc('created_at')->first();

    if ($activePromo) {
        $minRobux = (int) $activePromo->min_purchase_amount;
        $maxRobux = (int) $activePromo->max_purchase_amount;
        // promo_price adalah total harga untuk min_purchase_amount Robux.
        // hitung harga per 50 Robux: pricePer50 = promo_price * 50 / min_purchase_amount
        $pricePer50 = (int) round(($activePromo->promo_price * 50) / max(1, $activePromo->min_purchase_amount));
    } else {
        // fallback default (sama seperti sebelumnya)
        $minRobux = 50;
        $maxRobux = 5000;
        $pricePer50 = 7000;
    }

    return view('front.promo.index', compact('paymentMethods', 'robuxPromos', 'pricePer50', 'minRobux', 'maxRobux'));
   }
}
