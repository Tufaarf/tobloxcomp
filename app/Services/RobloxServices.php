<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RobloxServices
{
    /**
     * Menghitung harga gamepass yang harus dibuat user berdasarkan nominal Robux yang diinginkan.
     * Mengikuti list pemetaan yang diberikan user.
     */
    public function calculateRequiredPrice(int $amount): int
    {
        // Pemetaan khusus dari user (setiap kelipatan 100)
        $mapping = [
            100 => 143, 200 => 286, 300 => 429, 400 => 572, 500 => 714,
            600 => 857, 700 => 1000, 800 => 1143, 900 => 1286, 1000 => 1429,
            1100 => 1572, 1200 => 1715, 1300 => 1858, 1400 => 2000, 1500 => 2143,
            1600 => 2286, 1700 => 2429, 1800 => 2572, 1900 => 2715, 2000 => 2858,
            2100 => 3000, 2200 => 3143, 2300 => 3286, 2400 => 3429, 2500 => 3572,
            2600 => 3715, 2700 => 3858, 2800 => 4000, 2900 => 4143, 3000 => 4286,
            3100 => 4429, 3200 => 4572, 3300 => 4715, 3400 => 4858, 3500 => 5000,
            3600 => 5143, 3700 => 5286, 3800 => 5429, 3900 => 5572, 4000 => 5715,
            4100 => 5858, 4200 => 6000, 4300 => 6143, 4400 => 6286, 4500 => 6429,
            4600 => 6572, 4700 => 6715, 4800 => 6858, 4900 => 7000, 5000 => 7143,
            5100 => 7286, 5200 => 7429, 5300 => 7572, 5400 => 7715, 5500 => 7858,
            5600 => 8000, 5700 => 8143, 5800 => 8286, 5900 => 8429, 6000 => 8572,
            6100 => 8715, 6200 => 8858, 6300 => 9000, 6400 => 9143, 6500 => 9286,
            6600 => 9429, 6700 => 9572, 6800 => 9715, 6900 => 9858, 7000 => 10000,
            7100 => 10143, 7200 => 10286, 7300 => 10429, 7400 => 10572, 7500 => 10715,
            7600 => 10858, 7700 => 11000, 7800 => 11143, 7900 => 11286, 8000 => 11429,
            8100 => 11572, 8200 => 11715, 8300 => 11858, 8400 => 12000, 8500 => 12143,
            8600 => 12286, 8700 => 12429, 8800 => 12572, 8900 => 12715, 9000 => 12858,
            9100 => 13000, 9200 => 13143, 9300 => 13286, 9400 => 13429, 9500 => 13572,
            9600 => 13715, 9700 => 13858, 9800 => 14000, 9900 => 14143, 10000 => 14286,
        ];

        if (isset($mapping[$amount])) {
            return $mapping[$amount];
        }

        // Fallback jika tidak ada di list (menggunakan rumus ceil / 0.7)
        return (int) ceil($amount / 0.7);
    }

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
            'isCircular' => 'false',
        ]);

        if (!$response->successful() || empty($response->json('data.0.imageUrl'))) {
            Log::error("Failed to fetch avatar for UserID {$userId}: " . $response->body());
            return null;
        }

        $url = $response->json('data.0.imageUrl');
        Log::info("Fetched avatar for UserID {$userId}: {$url}");

        return $url;
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
            'accessFilter' => 'Public',
            'sortOrder' => 'Asc',
            'limit' => 50,
        ]);

        if (!$response->successful()) {
            Log::error("Roblox API Error (Games): {$response->status()} - {$response->body()}");
            return [];
        }

        $games = $response->json('data') ?? [];
        return array_map(fn($game) => $game['universeId'] ?? $game['id'], $games);
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
        $response = Http::get("https://apis.roblox.com/game-passes/v1/universes/{$universeId}/game-passes", [
            'passView' => 'Full',
            'pageSize' => 100,
        ]);

        if (!$response->successful()) {
            Log::error("Roblox API Error (Gamepass): {$response->status()} - {$response->body()}");
            return null;
        }

        $data = $response->json();
        $gamepasses = $data['gamePasses'] ?? $data['data'] ?? [];

        foreach ($gamepasses as $gamepass) {
            if (isset($gamepass['price']) && (int)$gamepass['price'] === (int)$price) {
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
