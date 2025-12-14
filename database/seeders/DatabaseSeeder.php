<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Chạy các seeder để tạo dữ liệu mẫu cho hệ thống.
     */
    public function run(): void
    {
        $this->command->info('🌱 Bắt đầu seed dữ liệu vào database...');
        $this->command->newLine();

        // Seed theo thứ tự phụ thuộc
        $this->command->info('👤 Đang tạo tài khoản người dùng...');
        $this->call(UserSeeder::class);
        $this->command->info('✅ Hoàn thành tạo tài khoản người dùng');
        $this->command->newLine();

        $this->command->info('📍 Đang tạo dữ liệu địa điểm...');
        $this->call(LocationSeeder::class);
        $this->command->info('✅ Hoàn thành tạo dữ liệu địa điểm');
        $this->command->newLine();

        $this->command->info('🏠 Đang tạo loại bất động sản...');
        $this->call(PropertyTypeSeeder::class);
        $this->command->info('✅ Hoàn thành tạo loại bất động sản');
        $this->command->newLine();

        $this->command->info('⭐ Đang tạo tính năng bất động sản...');
        $this->call(FeatureSeeder::class);
        $this->command->info('✅ Hoàn thành tạo tính năng bất động sản');
        $this->command->newLine();

        $this->command->info('📞 Đang tạo thông tin liên hệ...');
        $this->call(ContactSeeder::class);
        $this->command->info('✅ Hoàn thành tạo thông tin liên hệ');
        $this->command->newLine();

        $this->command->info('🏘️ Đang tạo bất động sản (có thể mất vài phút)...');
        $this->call(PropertySeeder::class);
        $this->command->info('✅ Hoàn thành tạo bất động sản (bao gồm hình ảnh và tính năng)');
        $this->command->newLine();

        $this->command->info('❤️ Đang tạo danh sách yêu thích...');
        $this->call(FavoritesSeeder::class);
        $this->command->info('✅ Hoàn thành tạo danh sách yêu thích');
        $this->command->newLine();

        $this->command->info('🎉 Hoàn tất seed dữ liệu! Database đã được tạo thành công.');
        $this->command->newLine();
        $this->command->info('📝 Thông tin đăng nhập Quản trị viên:');
        $this->command->line('   Email: admin@gmail.com');
        $this->command->line('   Mật khẩu: password');
    }
}
