<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class CommunityFaqSeeder extends Seeder
{
    public function run(): void
    {
        Community::firstOrCreate(
            ['header' => 'Mengalami kesulitan saat membeli Robux?'],
            [
                'description'   => '<p>Bergabunglah dengan komunitas kami. Klik ikon di bawah.</p>',
                'link_whatsapp' => 'https://wa.me/6281234567890',
                'link_instagram'=> 'https://instagram.com/yourbrand',
                'link_discord'  => 'https://discord.gg/xxxxxx',
                'is_active'     => true,
            ]
        );

        Faq::firstOrCreate(
            ['question' => 'Berapa lama waktu pengiriman Robux?'],
            [
                'answer'     => '<ol><li>Gamepass: ±5 hari</li><li>Login: ±3–5 jam</li></ol>',
                'sort_order' => 1,
                'is_active'  => true,
            ]
        );
    }
}
