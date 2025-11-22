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

        // Kiểm tra xem đã có contacts chưa
        $existingCount = DB::table('contacts')->count();
        if ($existingCount >= 120) {
            $this->command->line("   ℹ️  Đã có {$existingCount} contacts, bỏ qua seeding");
            return;
        }

        $userIds = DB::table('users')->pluck('id')->all();
        $totalContacts = 120 - $existingCount;

        for ($i = 1; $i <= $totalContacts; $i++) {
            DB::table('contacts')->insert([
                'name'       => $faker->name(),
                'phone'      => '09' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email'      => $faker->optional(0.7)->safeEmail(),
                'user_id'    => $faker->boolean(60) ? $faker->randomElement($userIds) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $this->command->line("   ✓ Đã tạo {$totalContacts} thông tin liên hệ");
    }
}
