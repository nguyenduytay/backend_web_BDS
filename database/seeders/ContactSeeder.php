<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $now = Carbon::now();

        $userIds = DB::table('users')->pluck('id')->all();

        $rows = [];
        for ($i = 1; $i <= 120; $i++) {
            $rows[] = [
                'name'       => $faker->name(),
                'phone'      => '09' . rand(00000000, 99999999),
                'email'      => $faker->optional(0.7)->safeEmail(),
                'user_id'    => $faker->boolean(60) ? $faker->randomElement($userIds) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('contacts')->insert($rows);
    }
}
