<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TopupPageController extends Controller
{
    public function show()
    {
        $pricePer50 = \App\Models\RobuxSetting::getPricePer50();

        $paymentMethods = \App\Models\PaymentMethod::all()
            ->map(function ($pm) {
                // Determine target based on type
                $target = $pm->account_number . ($pm->account_holder_name ? ' (' . $pm->account_holder_name . ')' : '');
                $type   = 'text';

                if ($pm->type === 'qris') {
                    $type = 'image';
                    $target = $pm->qris_image ? Storage::url($pm->qris_image) : asset('images/qris-placeholder.png');
                }

                return [
                    'code'   => $pm->code,
                    'name'   => $pm->name, // e.g. "BCA"
                    'fee'    => 0,         // No tax
                    'type'   => $type,     // text|image
                    'target' => $target,
                ];
            });

        return view('front.robux.topup', compact('pricePer50', 'paymentMethods'));
    }
}
