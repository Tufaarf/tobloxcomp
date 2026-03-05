<?php

namespace App\Services;

use App\Models\AccountOrder;
use App\Models\ItemOrder;
use App\Models\TopupOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $adminPhone;

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
        $this->adminPhone = config('services.fonnte.admin_phone', '');
    }

    /**
     * Kirim pesan WhatsApp via Fonnte API.
     */
    public function sendMessage(string $target, string $message): bool
    {
        if (empty($this->token) || empty($target)) {
            Log::warning('Fonnte: token atau target kosong, pesan tidak dikirim.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                        'target' => $target,
                        'message' => $message,
                    ]);

            if ($response->successful()) {
                Log::info('Fonnte: Pesan berhasil dikirim ke ' . $target);
                return true;
            }

            Log::error('Fonnte: Gagal kirim pesan. Response: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Fonnte: Exception saat kirim pesan: ' . $e->getMessage());
            return false;
        }
    }

    public function notifyNewItemOrder(ItemOrder $order): void
    {
        $message = "📦 *ORDER ITEM BARU*\n"
            . "━━━━━━━━━━━━━━━\n"
            . "🎮 Game: {$order->game_name}\n"
            . "📋 Item: {$order->item_name}\n"
            . "💰 Harga: Rp " . number_format($order->item_price, 0, ',', '.') . "\n"
            . "💳 Pembayaran: " . strtoupper($order->payment_method) . "\n"
            . "━━━━━━━━━━━━━━━\n"
            . "👤 Username: {$order->username}\n"
            . "📱 WhatsApp: {$order->wa_number}\n"
            . "📧 Email: " . ($order->email ?? '-') . "\n"
            . "━━━━━━━━━━━━━━━\n"
            . "💵 Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n"
            . "⏰ Waktu: " . now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') . "\n"
            . "📌 Status: " . ucfirst($order->status);

        $this->sendMessage($this->adminPhone, $message);
    }

    /**
     * Kirim notifikasi order robux baru ke admin.
     */
    public function notifyNewTopupOrder(TopupOrder $order): void
    {
        $message = "💎 *ORDER ROBUX BARU*\n"
            . "━━━━━━━━━━━━━━━\n"
            . "🆔 Order ID: {$order->order_id}\n"
            . "👤 Username: {$order->username}\n"
            . "🎮 Roblox ID: " . ($order->roblox_user_id ?? '-') . "\n"
            . "💎 Robux: " . number_format($order->robux_amount, 0, ',', '.') . "\n"
            . "💰 Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n"
            . "💳 Pembayaran: " . strtoupper($order->payment_method) . "\n"
            . "📱 WhatsApp: {$order->wa_number}\n"
            . "━━━━━━━━━━━━━━━\n"
            . "⏰ Waktu: " . now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') . "\n"
            . "📌 Status: " . ucfirst($order->status);

        $this->sendMessage($this->adminPhone, $message);
    }

    /**
     * Kirim notifikasi order akun baru ke admin.
     */
    public function notifyNewAccountOrder(AccountOrder $order): void
    {
        $message = "🎮 *ORDER AKUN BARU*\n"
            . "━━━━━━━━━━━━━━━\n"
            . "🆔 Order ID: {$order->order_id}\n"
            . "🎮 Akun: {$order->account_name}\n"
            . "💰 Harga: Rp " . number_format((float) $order->price, 0, ',', '.') . "\n"
            . "💳 Pembayaran: " . strtoupper($order->payment_method) . "\n"
            . "━━━━━━━━━━━━━━━\n"
            . "👤 Nama: {$order->name}\n"
            . "📧 Email: {$order->email}\n"
            . "📱 Phone: {$order->phone}\n"
            . "━━━━━━━━━━━━━━━\n"
            . "💵 Total: Rp " . number_format((float) $order->total_price, 0, ',', '.') . "\n"
            . "⏰ Waktu: " . now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') . "\n"
            . "📌 Status: " . ucfirst($order->status);

        $this->sendMessage($this->adminPhone, $message);
    }
}
