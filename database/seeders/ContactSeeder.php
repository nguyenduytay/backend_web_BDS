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

        $this->command->info('   → Đang tạo thông tin liên hệ (120 bản ghi)...');

        $userIds = DB::table('users')->pluck('id')->all();

        $rows = [];
        $totalContacts = 120;
        for ($i = 1; $i <= $totalContacts; $i++) {
            $rows[] = [
                'name'       => $faker->name(),
                'phone'      => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email'      => $faker->optional(0.7)->safeEmail(),
                'user_id'    => $faker->boolean(60) ? $faker->randomElement($userIds) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('contacts')->insert($rows);
        $this->command->line("   ✓ Đã tạo {$totalContacts} thông tin liên hệ");
    }
}
