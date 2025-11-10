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

        $propertyIds = DB::table('properties')->pluck('id')->all();

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
        }
    }
}
