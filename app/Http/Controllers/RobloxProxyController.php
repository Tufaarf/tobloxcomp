<?php

namespace App\Http\Controllers;

use App\Services\RobloxServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RobloxProxyController extends Controller
{
    protected $robloxService;

    public function __construct(RobloxServices $robloxService)
    {
        $this->robloxService = $robloxService;
    }

    /**
     * Metode utama untuk mengecek username dan mencari gamepass yang sesuai dengan nominal.
     */
    public function findPayableGamepass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:100',
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        $username = $request->input('username');
        $amount = (int) $request->input('amount');

        try {
            // 1. Cek User berdasarkan Username
            $user = $this->robloxService->resolveUser($username);
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Username tidak ditemukan atau tidak valid.']);
            }

            $userId = $user['id'];

            // 2. Cek apakah user punya experience
            $universeIds = $this->robloxService->getUniverseIds($userId);
            if (empty($universeIds)) {
                return response()->json(['status' => false, 'message' => 'Akun Roblox ini belum memiliki experience (game).']);
            }

            // 3. Iterasi setiap experience untuk mencari gamepass yang cocok
            $foundGamepass = null;
            foreach ($universeIds as $universeId) {
                $gamepass = $this->robloxService->findGamepassByPrice($universeId, $amount);
                if ($gamepass !== null) {
                    $foundGamepass = $gamepass;
                    break; // Hentikan pencarian jika sudah ditemukan
                }
            }

            if ($foundGamepass) {
                // Ambil avatar untuk ditampilkan di frontend
                $avatar = $this->robloxService->getAvatarHeadshot($userId);
                return response()->json([
                    'status' => true,
                    'message' => 'Gamepass yang sesuai ditemukan.',
                    'data' => [
                        'userId' => $userId,
                        'username' => $user['name'],
                        'displayName' => $user['displayName'],
                        'avatarUrl' => $avatar,
                        'gamepass' => $foundGamepass,
                    ]
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => "Tidak ditemukan gamepass dengan harga {$amount} Robux di experience manapun milik akun ini."
            ]);

        } catch (Throwable $e) {
            // Log error untuk debugging
            \Illuminate\Support\Facades\Log::error('Roblox API Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan saat menghubungi API Roblox.'], 500);
        }
    }

    /**
     * (Metode Lama - Diperbarui) Hanya untuk resolve username.
     */
    public function resolve(Request $request)
    {
        $data = $request->validate(['username' => 'required|string|min:3|max:100']);

        try {
            $user = $this->robloxService->resolveUser($data['username']);

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'Username tidak ditemukan.']);
            }

            $avatar = $this->robloxService->getAvatarHeadshot($user['id']);

            return response()->json([
                'status' => true,
                'userId' => $user['id'],
                'username' => $user['name'],
                'avatar' => $avatar,
            ]);
        } catch (Throwable $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghubungi API Roblox.']);
        }
    }

    /**
     * (Metode Lama - Diperbarui) Hanya untuk cek experience.
     */
    public function experience($userId)
    {
        try {
            $universeIds = $this->robloxService->getUniverseIds((int) $userId);
            $hasExperience = !empty($universeIds);

            return response()->json([
                'status' => $hasExperience,
                'message' => $hasExperience ? 'User memiliki experience.' : 'Akun belum punya experience.'
            ]);
        } catch (Throwable $e) {
            return response()->json(['status' => false, 'message' => 'Cek experience gagal.']);
        }
    }
}
