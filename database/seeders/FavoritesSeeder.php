<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class FavoritesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Lấy danh sách user và property hiện có
        $userIds     = DB::table('users')->pluck('id')->toArray();
        $propertyIds = DB::table('properties')->pluck('id')->toArray();

        $favorites = [];

        // Dùng set để tránh trùng user_id + property_id
        $usedPairs = [];

        for ($i = 0; $i < 100; $i++) {
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
        }

        DB::table('favorites')->insert($favorites);
    }
}
