<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RobloxServices
{
    /** Ambil userId dari username. */
    public function resolveUserId(string $username): ?int
    {
        $res = Http::post('https://users.roblox.com/v1/usernames/users', [
            'usernames' => [$username],
            'excludeBannedUsers' => true,
        ]);

        if (!$res->ok()) return null;

        $data = $res->json('data.0.id');
        return $data ? (int) $data : null;
    }

    /** Ambil daftar universe (experience) milik user. */
    public function universesByUser(int $userId, int $limit = 25): array
    {
        $res = Http::get("https://develop.roblox.com/v1/users/{$userId}/universes", [
            'sortOrder' => 'Asc',
            'limit' => $limit,
        ]);

        if (!$res->ok()) return [];

        $items = $res->json('data') ?? [];
        // kembalikan array [ ['id'=>universeId, 'name'=>title], ... ]
        $ids = array_map(fn($x) => $x['id'], $items);
        if (empty($ids)) return [];

        $games = Http::get('https://games.roblox.com/v1/games', [
            'universeIds' => implode(',', $ids),
        ]);

        if (!$games->ok()) {
            // fallback: hanya id tanpa nama
            return array_map(fn($id) => ['id' => $id, 'name' => 'Universe '.$id], $ids);
        }

        $data = $games->json('data') ?? [];
        return array_map(fn($g) => ['id' => $g['id'], 'name' => $g['name']], $data);
    }
}
