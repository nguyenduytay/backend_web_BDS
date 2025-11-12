<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PropertyImageSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $now   = Carbon::now();

        $this->command->info('   → Đang tạo hình ảnh cho bất động sản...');
        $propertyIds = DB::table('properties')->pluck('id')->all();
        $totalProperties = count($propertyIds);

        if ($totalProperties > 0) {
            $bar = $this->command->getOutput()->createProgressBar($totalProperties);
            $bar->setFormat('   %current%/%max% [%bar%] %percent:3s%% %message%');
            $bar->setMessage('Đang xử lý...');
            $bar->start();

            $totalImages = 0;
            foreach ($propertyIds as $pid) {
                $imgCount = $faker->numberBetween(3, 7);
                $primary  = $faker->numberBetween(1, $imgCount);

                $rows = [];
                for ($i = 1; $i <= $imgCount; $i++) {
                    $rows[] = [
                        'property_id' => $pid,
                        'image_path'  => "storage/properties/{$pid}/image_{$i}.jpg",
                        'image_name'  => "image_{$i}.jpg",
                        'is_primary'  => $i === $primary,
                        'sort_order'  => $i,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];
                }
                DB::table('property_images')->insert($rows);
                $totalImages += $imgCount;

                if (($pid % 50 == 0) || ($pid == end($propertyIds))) {
                    $bar->setMessage("Đã tạo hình ảnh cho {$pid} bất động sản");
                }
                $bar->advance();
            }

            $bar->setMessage('Hoàn thành!');
            $bar->finish();
            $this->command->newLine();
            $this->command->line("   ✓ Đã tạo {$totalImages} hình ảnh cho {$totalProperties} bất động sản");
        } else {
            $this->command->warn('   ⚠ Không có bất động sản nào để tạo hình ảnh');
        }
    }
}
