<?php

namespace App\Http\Controllers;

use App\Models\TopupOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TopupController extends Controller
{

    public function track(Request $request)
{
    $q = trim((string) $request->query('order_id', ''));

    $order = null;
    if ($q !== '') {
        $order = TopupOrder::query()
            ->where('order_id', $q)
            ->first();
    }

    return view('front.robux.track', [
        'query'  => $q,
        'order'  => $order,
    ]);
}

    public function store(Request $req)
    {
        $data = $req->validate([
            'username'        => 'required|string|min:3|max:100',
            'roblox_user_id'  => 'nullable|string',
            'avatar_url'      => 'nullable|string',
            'robux_amount'    => 'required|integer|min:50|max:5000',
            'payment_method'  => 'required|string|in:qris,gopay,seabank',
            'wa_number'       => 'required|string|min:10|max:20',
            'payment_proof'   => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            // jika kamu masih mengirim teks bukti di meta, ini opsional:
            'payment_proof_text' => 'nullable|string|max:1000',
        ]);

        // ambil metode dari config
        $methods = config('topup.methods', []);
        $method  = $methods[$data['payment_method']] ?? null;
        abort_unless($method, 422, 'Metode pembayaran tidak valid.');

        // hitung harga server-side (anti manipulasi)
        $pricePer50 = (int) config('topup.price_per_50', 7000);
        $base  = (int) round(($data['robux_amount'] / 50) * $pricePer50);
        $rate  = (float) $method['fee'];
        $tax   = (int) round($base * ($rate / 100));
        $total = $base + $tax;

        // generate ORDER ID unik: RBX-YYMMDD-ABCDE
        do {
            $orderId = 'RBX-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
        } while (TopupOrder::where('order_id', $orderId)->exists());

        // upload bukti pembayaran
        $proofPath = $req->file('payment_proof')->store('payment_proofs', 'public');

        // meta tambahan
        $meta = [];
        if ($req->filled('payment_proof_text')) {
            $meta['payment_proof_text'] = (string) $req->input('payment_proof_text');
        }

        // simpan order
        $order = TopupOrder::create([
            'order_id'       => $orderId,
            'username'       => $data['username'],
            'roblox_user_id' => $data['roblox_user_id'] ?? null,
            'avatar_url'     => $data['avatar_url'] ?? null,

            'robux_amount'   => $data['robux_amount'],
            'base_price'     => $base,
            'tax_rate'       => $rate,
            'tax_amount'     => $tax,
            'total_price'    => $total,

            'payment_method' => $data['payment_method'],
            'pay_to'         => $method['target'],
            'pay_to_type'    => $method['type'],
            'wa_number'      => $data['wa_number'],

            'payment_proof_path' => $proofPath,
            'status'         => TopupOrder::STAT_PENDING, // default
            'meta'           => $meta,
        ]);

        // buat invoice PNG sederhana (GD)
        $invoiceUrl = $this->generateInvoicePng($order);

        // tampilkan halaman popup sukses (user klik "Lanjut" -> diarahkan ke '/')
        return response()->view('front.robux.order-success', [
            'orderId'    => $order->order_id,
            'invoiceUrl' => $invoiceUrl,
            'redirectTo' => url(''), // halaman awal
        ]);
    }

    /**
     * Generate invoice PNG sederhana dan kembalikan URL publiknya.
     * Menggunakan ekstensi GD bawaan PHP. Jika GD tidak ada, akan return null.
     */
    private function generateInvoicePng(TopupOrder $o): ?string
    {
        if (!function_exists('imagecreatetruecolor')) {
            return null;
        }

        try {
            // kanvas
            $w = 1100; $h = 700;
            $im = imagecreatetruecolor($w, $h);

            // warna
            $white   = imagecolorallocate($im, 255, 255, 255);
            $black   = imagecolorallocate($im, 34, 36, 40);
            $muted   = imagecolorallocate($im, 107, 114, 128);
            $primary = imagecolorallocate($im, 241, 135, 171);
            $line    = imagecolorallocate($im, 230, 232, 236);

            // background
            imagefilledrectangle($im, 0, 0, $w, $h, $white);

            // header bar
            imagefilledrectangle($im, 0, 0, $w, 90, $primary);
            imagestring($im, 5, 40, 34, 'INVOICE', $white);
            imagestring($im, 5, $w - 360, 34, 'Order ID: ' . $o->order_id, $white);

            // konten
            $y = 130;
            imagestring($im, 5, 40, $y, 'Tanggal', $muted); imagestring($im, 5, 240, $y, now()->format('d M Y H:i'), $black); $y+=40;
            imagestring($im, 5, 40, $y, 'Username', $muted); imagestring($im, 5, 240, $y, (string) $o->username, $black); $y+=40;
            imagestring($im, 5, 40, $y, 'Roblox ID', $muted); imagestring($im, 5, 240, $y, (string) ($o->roblox_user_id ?? '-'), $black); $y+=40;
            imagestring($im, 5, 40, $y, 'WhatsApp', $muted); imagestring($im, 5, 240, $y, (string) $o->wa_number, $black); $y+=60;

            imageline($im, 40, $y, $w-40, $y, $line); $y+=30;

            imagestring($im, 5, 40, $y,  'Metode',    $muted); imagestring($im, 5, 240, $y, strtoupper($o->payment_method), $black); $y+=40;
            imagestring($im, 5, 40, $y,  'Robux',     $muted); imagestring($im, 5, 240, $y, number_format($o->robux_amount,0,',','.'), $black); $y+=40;
            imagestring($im, 5, 40, $y,  'Harga',     $muted); imagestring($im, 5, 240, $y, 'Rp '.number_format($o->base_price,0,',','.'), $black); $y+=40;
            imagestring($im, 5, 40, $y,  'Pajak',     $muted); imagestring($im, 5, 240, $y, number_format($o->tax_rate,2,',','.').'% (Rp '.number_format($o->tax_amount,0,',','.').')', $black); $y+=40;
            imagestring($im, 5, 40, $y,  'Total',     $muted); imagestring($im, 5, 240, $y, 'Rp '.number_format($o->total_price,0,',','.'), $black); $y+=60;

            // footer
            imageline($im, 40, $h-90, $w-40, $h-90, $line);
            imagestring($im, 3, 40, $h-70, 'Simpan invoice ini sebagai bukti transaksi.', $muted);

            // simpan
            Storage::disk('public')->makeDirectory('invoices');
            $rel = 'invoices/'.$o->order_id.'.png';
            $full = Storage::disk('public')->path($rel);
            imagepng($im, $full, 9);
            imagedestroy($im);

            return Storage::disk('public')->url($rel);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
