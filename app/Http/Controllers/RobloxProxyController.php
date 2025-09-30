<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RobloxProxyController extends Controller
{
    public function resolve(Request $request)
    {
        $data = $request->validate(['username' => 'required|string|min:3|max:100']);
        try {
            $resp = Http::asJson()->post('https://users.roblox.com/v1/usernames/users', [
                'usernames' => [$data['username']],
                'excludeBannedUsers' => true,
            ]);

            if (! $resp->successful()) {
                return response()->json(['status' => false, 'message' => 'Gagal menghubungi API Roblox.']);
            }

            $j = $resp->json();
            $user = $j['data'][0] ?? null;
            if (! $user || empty($user['id'])) {
                return response()->json(['status' => false, 'message' => 'Username tidak ditemukan.']);
            }

            $thumb = Http::get('https://thumbnails.roblox.com/v1/users/avatar-headshot', [
                'userIds' => $user['id'], 'size' => '150x150', 'format' => 'Png', 'isCircular' => 'false',
            ])->json();

            return response()->json([
                'status'   => true,
                'userId'   => $user['id'],
                'username' => $user['name'] ?? $data['username'],
                'avatar'   => $thumb['data'][0]['imageUrl'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghubungi API Roblox.']);
        }
    }

    public function experience($userId)
    {
        try {
            $resp = Http::get("https://games.roblox.com/v2/users/{$userId}/games", [
                'sortOrder' => 'Asc', 'limit' => 10,
            ]);

            if (! $resp->successful()) {
                return response()->json(['status' => false, 'message' => 'Cek experience gagal.']);
            }

            $has = ! empty($resp->json('data'));
            return response()->json(['status' => $has, 'message' => $has ? null : 'Akun belum punya experience.']);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => 'Cek experience gagal.']);
        }
    }
}
