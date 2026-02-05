<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AccountOrder;
use App\Models\AccountProduct;
use App\Models\Game; // Assuming Game model is needed for filtering
use App\Models\PaymentMethod; // Assuming we need payment methods
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountProduct::query()->where('stock', '>', 0);

        if ($request->has('game_id') && $request->game_id != 'all') {
            $query->where('game_id', $request->game_id);
        }

        $items = $query->latest()->get();
        $games = Game::has('accountProducts')->get(); // Optimally only get games that have products? Or all games.

        return view('front.account.index', compact('items', 'games'));
    }

    public function show($id)
    {
        $item = AccountProduct::with('game')->findOrFail($id);
        $paymentMethods = PaymentMethod::all()
            ->map(function ($pm) {
                $target = $pm->account_number . ($pm->account_holder_name ? ' (' . $pm->account_holder_name . ')' : '');
                $type = 'text';

                if ($pm->type === 'qris') {
                    $type = 'image';
                    $target = $pm->qris_image ? \Illuminate\Support\Facades\Storage::url($pm->qris_image) : asset('images/qris-placeholder.png');
                }

                return [
                    'code' => $pm->code,
                    'name' => $pm->name,
                    'fee' => 0,
                    'type' => $type,
                    'target' => $target,
                ];
            })->values();

        return view('front.account.show', compact('item', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_product_id' => 'required|exists:account_products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $product = AccountProduct::findOrFail($request->account_product_id);

        if ($product->stock < 1) {
            return back()->with('error', 'Stok habis!');
        }

        // Generate Order ID
        $orderId = 'ACC-' . strtoupper(Str::random(6)) . '-' . now()->format('dmY');

        $order = AccountOrder::create([
            'order_id' => $orderId,
            'account_product_id' => $product->id,
            'game_id' => $product->game_id,
            'account_name' => $product->name,
            'price' => $product->price,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'payment_method' => $request->payment_method,
            'total_price' => $product->price, // Assuming no extra fees for now
            'status' => 'review',
        ]);

        // Reduce stock?
        // Usually stock is reduced on payment or immediately. Let's reduce immediately for now or keep it.
        // User didn't specify, but "stok nya berapa" implies management.
        // I'll decrement stock.
        $product->decrement('stock');

        // Allow JSON response for AJAX/Fetch requests (Fix for Network Error)
        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            session()->flash('success', 'Pesanan berhasil dibuat! Silahkan simpan Order ID anda.');
            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat!',
                'redirect_url' => route('account.track', ['order_id' => $orderId]),
                'order_id' => $orderId
            ]);
        }

        return redirect()->route('account.track', ['order_id' => $orderId])->with('success', 'Pesanan berhasil dibuat! Silahkan simpan Order ID anda.');
    }

    public function track(Request $request)
    {
        $query = $request->input('order_id');
        $order = null;

        if ($query) {
            $order = AccountOrder::where('order_id', $query)->first();
        }

        return view('front.account.track', compact('query', 'order'));
    }
}
