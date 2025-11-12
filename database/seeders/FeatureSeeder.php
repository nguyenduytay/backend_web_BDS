<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $this->command->info('   → Đang tạo tính năng bất động sản...');

        $features = [
            ['name' => 'Hồ bơi', 'icon' => 'fa-swimming-pool', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chỗ đậu xe', 'icon' => 'fa-car', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Phòng gym', 'icon' => 'fa-dumbbell', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sân vườn', 'icon' => 'fa-tree', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ban công', 'icon' => 'fa-building', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'An ninh 24/7', 'icon' => 'fa-shield-alt', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thang máy', 'icon' => 'fa-elevator', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nội thất cơ bản', 'icon' => 'fa-couch', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sân thượng', 'icon' => 'fa-sun', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View biển', 'icon' => 'fa-water', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('features')->insert($features);
        $this->command->line('   ✓ Đã tạo ' . count($features) . ' tính năng bất động sản');
    }
}
