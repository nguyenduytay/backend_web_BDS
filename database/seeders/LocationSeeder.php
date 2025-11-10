<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            'Hà Nội' => ['Ba Đình', 'Hoàn Kiếm', 'Cầu Giấy', 'Đống Đa', 'Hai Bà Trưng', 'Thanh Xuân', 'Nam Từ Liêm', 'Bắc Từ Liêm', 'Hà Đông'],
            'Hồ Chí Minh' => ['Quận 1', 'Quận 3', 'Quận 7', 'Quận 10', 'Bình Thạnh', 'Phú Nhuận', 'Tân Bình', 'Tân Phú', 'Thủ Đức'],
            'Đà Nẵng' => ['Hải Châu', 'Sơn Trà', 'Thanh Khê', 'Ngũ Hành Sơn', 'Cẩm Lệ', 'Liên Chiểu'],
            'Cần Thơ' => ['Ninh Kiều', 'Cái Răng', 'Bình Thủy', 'Ô Môn'],
            'Hải Phòng' => ['Hồng Bàng', 'Ngô Quyền', 'Lê Chân', 'Hải An'],
        ];

        $rows = [];
        foreach ($data as $city => $districts) {
            foreach ($districts as $district) {
                $rows[] = [
                    'city'       => $city,
                    'district'   => $district,
                    'slug'       => Str::slug($city . '-' . $district),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('locations')->insert($rows);
    }
}
