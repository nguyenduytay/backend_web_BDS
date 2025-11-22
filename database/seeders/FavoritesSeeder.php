<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoritesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $this->command->info('   → Đang tạo danh sách yêu thích...');

        // Lấy danh sách user và property hiện có
        $userIds     = DB::table('users')->pluck('id')->toArray();
        $propertyIds = DB::table('properties')->pluck('id')->toArray();

        if (empty($userIds) || empty($propertyIds)) {
            $this->command->warn('   ⚠ Không có user hoặc property để tạo danh sách yêu thích');
            return;
        }

        // Kiểm tra xem đã có favorites chưa
        $existingCount = DB::table('favorites')->count();
        if ($existingCount >= 100) {
            $this->command->line("   ℹ️  Đã có {$existingCount} favorites, bỏ qua seeding");
            return;
        }

        $favorites      = [];
        $totalFavorites = 100 - $existingCount;

        // Dùng set để tránh trùng user_id + property_id
        $usedPairs = [];

        $bar = $this->command->getOutput()->createProgressBar($totalFavorites);
        $bar->setFormat('   %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage('Đang xử lý...');
        $bar->start();

        $attempts = 0;
        $maxAttempts = $totalFavorites * 10; // Giới hạn số lần thử

        for ($i = 0; $i < $totalFavorites && $attempts < $maxAttempts; $attempts++) {
            $userId     = $faker->randomElement($userIds);
            $propertyId = $faker->randomElement($propertyIds);

            $pair = $userId . '-' . $propertyId;

            // Kiểm tra xem đã tồn tại trong database hoặc trong batch hiện tại chưa
            $exists = isset($usedPairs[$pair]) ||
                     DB::table('favorites')
                       ->where('user_id', $userId)
                       ->where('property_id', $propertyId)
                       ->exists();

            if ($exists) {
                continue; // Bỏ qua và thử lại
            }

            $usedPairs[$pair] = true;

            $favorites[] = [
                'user_id'     => $userId,
                'property_id' => $propertyId,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ];

            if (($i + 1) % 25 == 0) {
                $bar->setMessage("Đã tạo " . ($i + 1) . "/{$totalFavorites} yêu thích");
            }
            $bar->advance();
            $i++;
        }

        if (!empty($favorites)) {
            foreach ($favorites as $favorite) {
                DB::table('favorites')->updateOrInsert(
                    [
                        'user_id' => $favorite['user_id'],
                        'property_id' => $favorite['property_id'],
                    ],
                    $favorite
                );
            }
        }

        $bar->setMessage('Hoàn thành!');
        $bar->finish();
        $this->command->newLine();
        $this->command->line("   ✓ Đã tạo {$totalFavorites} bản ghi yêu thích");
    }
}
