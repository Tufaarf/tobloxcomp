<?php

namespace App\Http\Controllers;

use App\Models\ItemOrder;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'wa_number' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'payment_method' => 'required|string',
            'product_id' => 'required|exists:items,id',
            'game_id' => 'required',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Upload file bukti pembayaran
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Ambil data produk dengan relasi game
            $product = Item::with('game')->findOrFail($request->product_id);

            // Hitung total dengan tax dari config
            $paymentMethods = config('topup.methods');
            $selectedMethod = $paymentMethods[$request->payment_method] ?? null;
            $taxRate = $selectedMethod['fee'] ?? 0;
            $tax = round($product->price * ($taxRate / 100));
            $totalPrice = $product->price + $tax;

            // Simpan data order dengan informasi game
            ItemOrder::create([
                'username' => $request->username,
                'wa_number' => $request->wa_number,
                'email' => $request->email,
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofPath,
                'item_id' => $product->id,
                'game_id' => $product->game->id,
                'game_name' => $product->game->name,
                'item_name' => $product->name,
                'item_price' => $product->price,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat!, Harap Tunggu Konfirmasi dari admin melalui whatsapp',
                'redirect_url' => route('front.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pesanan. ' . $e->getMessage(),
            ], 500);
        }
    }

    // Halaman sukses
    public function success()
    {
        return view('front.index');
    }

}
