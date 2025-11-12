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

        $favorites      = [];
        $totalFavorites = 100;

        // Dùng set để tránh trùng user_id + property_id
        $usedPairs = [];

        $bar = $this->command->getOutput()->createProgressBar($totalFavorites);
        $bar->setFormat('   %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage('Đang xử lý...');
        $bar->start();

        for ($i = 0; $i < $totalFavorites; $i++) {
            $userId     = $faker->randomElement($userIds);
            $propertyId = $faker->randomElement($propertyIds);

            $pair = $userId . '-' . $propertyId;

            // Nếu đã tồn tại thì bỏ qua, tìm cái khác
            if (isset($usedPairs[$pair])) {
                $i--;
                continue;
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
        }

        DB::table('favorites')->insert($favorites);

        $bar->setMessage('Hoàn thành!');
        $bar->finish();
        $this->command->newLine();
        $this->command->line("   ✓ Đã tạo {$totalFavorites} bản ghi yêu thích");
    }
}
