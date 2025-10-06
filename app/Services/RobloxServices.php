<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RobloxServices
{
    /**
     * Mengambil detail pengguna dari username.
     * Mengembalikan array berisi id, name, dan displayName.
     *
     * @param string $username
     * @return array|null
     */
    public function resolveUser(string $username): ?array
    {
        $response = Http::asJson()->post('https://users.roblox.com/v1/usernames/users', [
            'usernames' => [$username],
            'excludeBannedUsers' => true,
        ]);

        if (!$response->successful() || empty($response->json('data.0'))) {
            return null;
        }

        return $response->json('data.0');
    }

    /**
     * Mengambil URL avatar headshot pengguna.
     *
     * @param int $userId
     * @return string|null
     */
    public function getAvatarHeadshot(int $userId): ?string
    {
        $response = Http::get('https://thumbnails.roblox.com/v1/users/avatar-headshot', [
            'userIds' => $userId,
            'size' => '150x150',
            'format' => 'Png',
            'isCircular' => false,
        ]);

        if (!$response->successful() || empty($response->json('data.0.imageUrl'))) {
            return null;
        }

        return $response->json('data.0.imageUrl');
    }

    /**
     * Mengambil daftar ID Universe (experience) yang dimiliki oleh pengguna.
     *
     * @param int $userId
     * @return array
     */
    public function getUniverseIds(int $userId): array
    {
        $response = Http::get("https://games.roblox.com/v2/users/{$userId}/games", [
            'sortOrder' => 'Asc',
            'limit' => 50, // Ambil hingga 50 experience, bisa disesuaikan
        ]);

        if (!$response->successful()) {
            return [];
        }

        $games = $response->json('data') ?? [];

        // Ambil hanya ID universe-nya
        return array_map(fn($game) => $game['id'], $games);
    }

    /**
     * Mencari Game Pass dengan harga spesifik di dalam sebuah Universe.
     * Mengembalikan detail gamepass jika ditemukan.
     *
     * @param int $universeId
     * @param int $price
     * @return array|null
     */
    public function findGamepassByPrice(int $universeId, int $price): ?array
    {
        // API untuk mengambil gamepass dari sebuah experience/universe
        $response = Http::get("https://games.roblox.com/v1/games/{$universeId}/game-passes", [
            'sortOrder' => 'Asc',
            'limit' => 100, // Cek hingga 100 gamepass per experience
        ]);

        if (!$response->successful()) {
            return null;
        }

        $gamepasses = $response->json('data') ?? [];

        foreach ($gamepasses as $gamepass) {
            // Cek apakah gamepass dijual dan harganya cocok
            if (isset($gamepass['price']) && $gamepass['price'] === $price) {
                return [
                    'id' => $gamepass['id'],
                    'name' => $gamepass['name'],
                    'price' => $gamepass['price'],
                    'universeId' => $universeId,
                ];
            }
        }

        return null;
    }
}
