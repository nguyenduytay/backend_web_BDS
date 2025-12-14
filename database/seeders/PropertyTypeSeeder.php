<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $this->command->info('   → Đang tạo loại bất động sản...');

        $types = [
            [
                'type'       => 'apartment',
                'name'       => 'Căn hộ',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type'       => 'house',
                'name'       => 'Nhà phố',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type'       => 'villa',
                'name'       => 'Biệt thự',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type'       => 'shophouse',
                'name'       => 'Shophouse',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type'       => 'land',
                'name'       => 'Đất nền',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'type'       => 'office',
                'name'       => 'Văn phòng',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($types as $type) {
            DB::table('property_types')->updateOrInsert(
                ['type' => $type['type']],
                $type
            );
        }
        $this->command->line(
            '   ✓ Đã tạo ' . count($types) .
            ' loại bất động sản: Căn hộ, Nhà phố, Biệt thự, Shophouse, Đất nền, Văn phòng'
        );
    }
}
