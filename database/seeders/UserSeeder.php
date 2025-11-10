<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Admin
        DB::table('users')->insert([
            'name'              => 'Super Admin',
            'email'             => 'admin@example.com',
            'password'          => Hash::make('admin123'),
            'role'              => 'admin',
            'remember_token'    => Str::random(10),
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        // Agents
        $agents = [];
        for ($i = 1; $i <= 10; $i++) {
            $agents[] = [
                'name'           => "Agent $i",
                'email'          => "agent$i@example.com",
                'password'       => Hash::make('password'),
                'role'           => 'agent',
                'phone'          => '09' . rand(00000000, 99999999),
                'remember_token' => Str::random(10),
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }
        DB::table('users')->insert($agents);

        // Users
        $users = [];
        for ($i = 1; $i <= 50; $i++) {
            $users[] = [
                'name'           => "User $i",
                'email'          => "user$i@example.com",
                'password'       => Hash::make('password'),
                'role'           => 'user',
                'phone'          => '09' . rand(00000000, 99999999),
                'remember_token' => Str::random(10),
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }
        DB::table('users')->insert($users);
    }
}
