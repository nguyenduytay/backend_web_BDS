<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PropertyFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $this->command->info('   → Đang liên kết tính năng với bất động sản...');
        $propertyIds = DB::table('properties')->pluck('id')->all();
        $featureIds  = DB::table('features')->pluck('id')->all();
        $totalProperties = count($propertyIds);

        if ($totalProperties > 0) {
            $bar = $this->command->getOutput()->createProgressBar($totalProperties);
            $bar->setFormat('   %current%/%max% [%bar%] %percent:3s%% %message%');
            $bar->setMessage('Đang xử lý...');
            $bar->start();

            $totalLinks = 0;
            foreach ($propertyIds as $pid) {
                shuffle($featureIds);
                $count = $faker->numberBetween(3, min(7, count($featureIds)));
                $pick  = array_slice($featureIds, 0, $count);

                foreach ($pick as $fid) {
                    DB::table('property_features')->updateOrInsert(
                        [
                            'property_id' => $pid,
                            'feature_id'  => $fid,
                        ],
                        [] // không cần update gì thêm
                    );
                }
                $totalLinks += $count;

                if (($pid % 50 == 0) || ($pid == end($propertyIds))) {
                    $bar->setMessage("Đã xử lý {$pid} bất động sản");
                }
                $bar->advance();
            }

            $bar->setMessage('Hoàn thành!');
            $bar->finish();
            $this->command->newLine();
            $this->command->line("   ✓ Đã liên kết {$totalLinks} tính năng cho {$totalProperties} bất động sản");
        } else {
            $this->command->warn('   ⚠ Không có bất động sản nào để liên kết tính năng');
        }
    }
}
