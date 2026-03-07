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
        Log::info("Fetching universes for UserID: {$userId}");
        // Gunakan v2/users/{userId}/games untuk mengambil list game user
        $response = Http::get("https://games.roblox.com/v2/users/{$userId}/games", [
            'accessFilter' => 'Public',
            'sortOrder' => 'Asc',
            'limit' => 50,
        ]);

        if (!$response->successful()) {
            Log::error("Failed to fetch games for UserID {$userId}: Status {$response->status()} - " . $response->body());
            return [];
        }

        $data = $response->json();
        Log::info("Games API Response for UserID {$userId}: " . json_encode($data));
        
        $games = $data['data'] ?? [];

        if (empty($games)) {
            Log::warning("No public games found for UserID {$userId}");
        }

        // PENTING: Ambil 'universeId' bukan 'id' (Place ID)
        $universeIds = array_map(fn($game) => $game['universeId'] ?? $game['id'], $games);
        Log::info("Resolved Universe IDs for UserID {$userId}: " . implode(', ', $universeIds));

        return $universeIds;
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
        Log::info("Searching gamepasses in UniverseID: {$universeId} for Price: {$price}");
        // Endpoint baru sejak v1/games/{universeId}/game-passes didepresiasi (Aug 2025)
        // Gunakan passView=Full agar field 'price' muncul di response
        $response = Http::get("https://apis.roblox.com/game-passes/v1/universes/{$universeId}/game-passes", [
            'passView' => 'Full',
            'pageSize' => 100,
        ]);

        if (!$response->successful()) {
            Log::error("Failed to fetch game-passes for UniverseID {$universeId}: Status {$response->status()} - " . $response->body());
            return null;
        }

        $data = $response->json();
        Log::info("Gamepasses API Response for UniverseID {$universeId}: " . json_encode($data));
        
        // BUG FIX: apis.roblox.com menggunakan key 'gamePasses', bukan 'data'
        $gamepasses = $data['gamePasses'] ?? $data['data'] ?? [];

        foreach ($gamepasses as $gamepass) {
            Log::info("Checking Gamepass: ID {$gamepass['id']}, Name '{$gamepass['name']}', Price " . ($gamepass['price'] ?? 'N/A'));
            // Cek apakah gamepass dijual dan harganya cocok
            if (isset($gamepass['price']) && (int)$gamepass['price'] === (int)$price) {
                Log::info("MATCH FOUND: Gamepass ID {$gamepass['id']} matches price {$price}");
                return [
                    'id' => $gamepass['id'],
                    'name' => $gamepass['name'],
                    'price' => $gamepass['price'],
                    'universeId' => $universeId,
                ];
            }
        }

        Log::info("No matching gamepass found in UniverseID {$universeId}");
        return null;
    }
}
