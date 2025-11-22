<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $now = Carbon::now();

        $this->command->info('   → Đang tạo tài khoản Admin...');

        // Tài khoản Admin cố định
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@gmail.com'],
            [
                'name'              => 'Quản trị viên',
                'email'             => 'admin@gmail.com',
                'password'          => Hash::make('password'),
                'role'              => 'admin',
                'phone'             => '0900000000',
                'remember_token'    => Str::random(10),
                'created_at'        => $now,
                'updated_at'        => $now,
            ]
        );
        $this->command->line('   ✓ Đã tạo tài khoản Admin (admin@gmail.com / password)');

        $this->command->info('   → Đang tạo tài khoản Agent (10 tài khoản)...');
        // Agents
        $agents = [];
        $agentNames = [
            'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Văn Cường', 'Phạm Thị Dung',
            'Hoàng Văn Em', 'Vũ Thị Phương', 'Đặng Văn Giang', 'Bùi Thị Hoa',
            'Đỗ Văn Hùng', 'Ngô Thị Lan'
        ];

        for ($i = 0; $i < 10; $i++) {
            $email = "agent" . ($i + 1) . "@example.com";
            DB::table('users')->updateOrInsert(
                ['email' => $email],
                [
                    'name'           => isset($agentNames[$i]) ? $agentNames[$i] : "Agent " . ($i + 1),
                    'email'          => $email,
                    'password'       => Hash::make('password'),
                    'role'           => 'agent',
                    'phone'          => '09' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                    'remember_token' => Str::random(10),
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]
            );
        }
        $this->command->line('   ✓ Đã tạo 10 tài khoản Agent');

        $this->command->info('   → Đang tạo tài khoản User (50 tài khoản)...');
        // Users
        for ($i = 1; $i <= 50; $i++) {
            $email = "user{$i}@example.com";
            DB::table('users')->updateOrInsert(
                ['email' => $email],
                [
                    'name'           => $faker->name(),
                    'email'          => $email,
                    'password'       => Hash::make('password'),
                    'role'           => 'user',
                    'phone'          => '09' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                    'remember_token' => Str::random(10),
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]
            );
        }
        $this->command->line('   ✓ Đã tạo 50 tài khoản User');

        $totalUsers = DB::table('users')->count();
        $this->command->info("   📊 Tổng cộng: {$totalUsers} tài khoản đã được tạo");
    }
}
