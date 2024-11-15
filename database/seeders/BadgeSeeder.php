<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'title' => 'Engineering Manager',
                'icon'  => '👨‍💻',
                'order' => 1,
            ],
            [
                'title' => 'Laravel Developer',
                'icon'  => '🚀',
                'order' => 2,
            ],
            [
                'title' => 'Army Veteran',
                'icon'  => '🎖️',
                'order' => 3,
            ],
            [
                'title' => 'Cycling Enthusiast',
                'icon'  => '🚴',
                'order' => 4,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
