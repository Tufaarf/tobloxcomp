<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TopupPageController extends Controller
{
    public function show()
    {
        $pricePer50 = (int) config('topup.price_per_50', 7000);

        $paymentMethods = collect(config('topup.methods', []))
            ->map(function ($m, $code) {
                $target = $m['target'];
                if (($m['type'] ?? 'text') === 'image') {
                    // Buat URL absolut agar tidak 404
                    if (Str::startsWith($target, ['http://', 'https://'])) {
                        // sudah URL
                    } elseif (Storage::disk('public')->exists($target)) {
                        $target = Storage::url($target); // /storage/...
                    } else {
                        // fallback ke public/ jika ada
                        $target = asset($target);
                    }
                }
                return [
                    'code'   => $code,
                    'name'   => $m['name'],
                    'fee'    => (float) $m['fee'],
                    'type'   => $m['type'],
                    'target' => $target, // sudah final (URL untuk image, teks untuk yang lain)
                ];
            })->values();

        return view('front.robux.topup', compact('pricePer50', 'paymentMethods'));
    }
}
