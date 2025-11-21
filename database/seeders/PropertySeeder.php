<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $now   = Carbon::now();

        $this->command->info('   → Đang tải dữ liệu tham chiếu...');
        $locationIds = DB::table('locations')->pluck('id')->all();
        $contactIds  = DB::table('contacts')->pluck('id')->all();
        $featureIds  = DB::table('features')->pluck('id')->all();
        $types       = DB::table('property_types')->pluck('type', 'id')->toArray(); // [id => type]
        $agentIds    = DB::table('users')->where('role', 'agent')->pluck('id')->all();
        $adminId     = DB::table('users')->where('role', 'admin')->value('id');
        $this->command->line('   ✓ Đã tải dữ liệu tham chiếu');

        // Phạm vi toạ độ Việt Nam (xấp xỉ)
        $latMin = 8.179;
        $latMax = 23.392;
        $lngMin = 102.144;
        $lngMax = 109.469;

        // Kiểm tra xem đã có properties chưa
        $existingCount = DB::table('properties')->count();
        if ($existingCount >= 300) {
            $this->command->line("   ℹ️  Đã có {$existingCount} properties, bỏ qua seeding");
            return;
        }

        $statuses        = ['available', 'sold', 'rented', 'pending'];
        $totalProperties = 300 - $existingCount;

        // Mẫu đường phố tiếng Việt
        $streetNames = [
            'Đường Nguyễn Huệ', 'Đường Lê Lợi', 'Đường Trần Hưng Đạo', 'Đường Lý Thường Kiệt',
            'Đường Hoàng Diệu', 'Đường Phạm Ngũ Lão', 'Đường Bà Triệu', 'Đường Hai Bà Trưng',
            'Đường Lê Duẩn', 'Đường Nguyễn Trãi', 'Đường Võ Thị Sáu', 'Đường Điện Biên Phủ',
            'Đường Cách Mạng Tháng Tám', 'Đường Nguyễn Thị Minh Khai', 'Đường Pasteur',
            'Đường Nam Kỳ Khởi Nghĩa', 'Đường Võ Văn Tần', 'Đường Nguyễn Đình Chiểu',
            'Đường Đinh Tiên Hoàng', 'Đường Lý Tự Trọng', 'Đường Nguyễn Văn Cừ'
        ];

        // Mẫu title và description bằng tiếng Việt
        $titleTemplates = [
            'apartment' => [
                'Căn hộ cao cấp', 'Căn hộ hiện đại', 'Căn hộ view đẹp', 'Căn hộ tiện nghi',
                'Căn hộ sang trọng', 'Căn hộ mới xây', 'Căn hộ full nội thất', 'Căn hộ giá tốt'
            ],
            'house' => [
                'Nhà phố mặt tiền', 'Nhà phố 3 tầng', 'Nhà phố hiện đại', 'Nhà phố kinh doanh',
                'Nhà phố mới xây', 'Nhà phố giá rẻ', 'Nhà phố đẹp', 'Nhà phố tiện nghi'
            ],
            'villa' => [
                'Biệt thự sang trọng', 'Biệt thự view biển', 'Biệt thự hiện đại', 'Biệt thự cao cấp',
                'Biệt thự mới xây', 'Biệt thự đẹp', 'Biệt thự tiện nghi', 'Biệt thự độc đáo'
            ],
            'shophouse' => [
                'Shophouse mặt tiền', 'Shophouse kinh doanh', 'Shophouse hiện đại', 'Shophouse giá tốt',
                'Shophouse mới xây', 'Shophouse đẹp', 'Shophouse tiện nghi', 'Shophouse vị trí đẹp'
            ],
            'land' => [
                'Đất nền mặt tiền', 'Đất nền giá tốt', 'Đất nền vị trí đẹp', 'Đất nền đầu tư',
                'Đất nền pháp lý rõ', 'Đất nền tiềm năng', 'Đất nền thổ cư', 'Đất nền dự án'
            ],
            'office' => [
                'Văn phòng cho thuê', 'Văn phòng hiện đại', 'Văn phòng giá tốt', 'Văn phòng vị trí đẹp',
                'Văn phòng cao cấp', 'Văn phòng tiện nghi', 'Văn phòng mới', 'Văn phòng đẹp'
            ]
        ];

        $descriptionTemplates = [
            'apartment' => [
                'Căn hộ đẹp, thiết kế hiện đại, đầy đủ tiện nghi. Vị trí thuận lợi, gần trung tâm, giao thông thuận tiện. Phù hợp cho gia đình trẻ hoặc người độc thân.',
                'Căn hộ cao cấp với view đẹp, không gian thoáng mát. Nội thất đầy đủ, sẵn sàng vào ở ngay. An ninh tốt, tiện ích xung quanh đầy đủ.',
                'Căn hộ mới xây, thiết kế tối ưu không gian. Gần trường học, bệnh viện, siêu thị. Phù hợp đầu tư hoặc ở.',
                'Căn hộ sang trọng, đầy đủ tiện ích: hồ bơi, gym, sân vườn. Vị trí đắc địa, giá trị đầu tư cao.'
            ],
            'house' => [
                'Nhà phố đẹp, thiết kế hiện đại, phù hợp ở hoặc kinh doanh. Mặt tiền rộng, vị trí đẹp, giá trị đầu tư cao.',
                'Nhà phố mới xây, kiến trúc đẹp, không gian rộng rãi. Gần trung tâm, giao thông thuận tiện. Phù hợp gia đình đông người.',
                'Nhà phố mặt tiền, kinh doanh tốt. Thiết kế tối ưu, đầy đủ tiện nghi. Vị trí đắc địa, tiềm năng sinh lời cao.',
                'Nhà phố hiện đại, nội thất đầy đủ. Sân vườn rộng, không gian thoáng mát. Phù hợp ở hoặc cho thuê.'
            ],
            'villa' => [
                'Biệt thự sang trọng, thiết kế độc đáo. Diện tích rộng, sân vườn đẹp, hồ bơi riêng. Vị trí đắc địa, giá trị cao.',
                'Biệt thự cao cấp, view đẹp, không gian thoáng mát. Nội thất sang trọng, đầy đủ tiện ích. Phù hợp gia đình thượng lưu.',
                'Biệt thự mới xây, kiến trúc hiện đại. Sân vườn rộng, hồ bơi, phòng gym. An ninh tốt, tiện ích đầy đủ.',
                'Biệt thự đẹp, thiết kế tinh tế. Không gian sống cao cấp, đầy đủ tiện nghi. Vị trí yên tĩnh, phù hợp nghỉ dưỡng.'
            ],
            'shophouse' => [
                'Shophouse mặt tiền, kinh doanh tốt. Thiết kế hiện đại, không gian rộng. Vị trí đắc địa, tiềm năng sinh lời cao.',
                'Shophouse mới xây, kiến trúc đẹp. Phù hợp mở cửa hàng, văn phòng. Giao thông thuận tiện, đông dân cư.',
                'Shophouse giá tốt, đầu tư hiệu quả. Mặt tiền rộng, thiết kế tối ưu. Vị trí trung tâm, tiềm năng phát triển.',
                'Shophouse cao cấp, thiết kế sang trọng. Phù hợp kinh doanh cao cấp. Vị trí đẹp, giá trị đầu tư cao.'
            ],
            'land' => [
                'Đất nền mặt tiền, pháp lý rõ ràng. Vị trí đẹp, tiềm năng phát triển cao. Phù hợp đầu tư hoặc xây dựng.',
                'Đất nền giá tốt, đầu tư hiệu quả. Gần trung tâm, hạ tầng đầy đủ. Pháp lý minh bạch, sổ đỏ chính chủ.',
                'Đất nền vị trí đắc địa, tiềm năng tăng giá. Giao thông thuận tiện, hạ tầng tốt. Phù hợp xây nhà hoặc đầu tư.',
                'Đất nền thổ cư, pháp lý đầy đủ. Diện tích đẹp, mặt tiền rộng. Vị trí yên tĩnh, phù hợp xây biệt thự.'
            ],
            'office' => [
                'Văn phòng cho thuê, vị trí đẹp. Thiết kế hiện đại, không gian rộng rãi. Phù hợp công ty vừa và nhỏ.',
                'Văn phòng cao cấp, đầy đủ tiện ích. Gần trung tâm, giao thông thuận tiện. Giá thuê hợp lý, phù hợp doanh nghiệp.',
                'Văn phòng mới, thiết kế tối ưu. Không gian làm việc thoáng mát. Tiện ích đầy đủ: bãi đỗ xe, thang máy.',
                'Văn phòng hiện đại, giá tốt. Vị trí thuận lợi, dễ tìm. Phù hợp startup hoặc công ty mới thành lập.'
            ]
        ];

        $this->command->info("   → Đang tạo {$totalProperties} bất động sản...");
        $bar = $this->command->getOutput()->createProgressBar($totalProperties);
        $bar->setFormat('   %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage('Đang xử lý...');
        $bar->start();

        for ($i = 1; $i <= $totalProperties; $i++) {
            // Lấy random id + type
            $typeId = $faker->randomElement(array_keys($types));
            $type   = $types[$typeId];

            // Lấy location để tạo title phù hợp
            $locationId = $faker->randomElement($locationIds);
            $location = DB::table('locations')->where('id', $locationId)->first();
            $cityName = $location ? $location->city : '';

            // Tạo title và description bằng tiếng Việt
            $titleTemplate = $faker->randomElement($titleTemplates[$type] ?? $titleTemplates['apartment']);
            $title = $titleTemplate . ' tại ' . $cityName;
            
            $description = $faker->optional(0.9)->randomElement($descriptionTemplates[$type] ?? $descriptionTemplates['apartment']);

            // Giá gợi ý theo loại
            $price = match ($type) {
                'apartment' => $faker->numberBetween(900_000_000, 5_000_000_000),
                'house'     => $faker->numberBetween(1_500_000_000, 12_000_000_000),
                'villa'     => $faker->numberBetween(8_000_000_000, 60_000_000_000),
                'shophouse' => $faker->numberBetween(5_000_000_000, 40_000_000_000),
                'land'      => $faker->numberBetween(800_000_000, 20_000_000_000),
                'office'    => $faker->numberBetween(1_000_000_000, 20_000_000_000),
                default     => $faker->numberBetween(1_000_000_000, 10_000_000_000),
            };

            $createdBy = $faker->boolean(80)
                ? ($faker->boolean(90) ? $faker->randomElement($agentIds) : $adminId)
                : null;

            $propertyId = DB::table('properties')->insertGetId([
                'title'            => $title,
                'description'      => $description,
                'location_id'      => $locationId,
                'property_type_id' => $typeId, // ✅ đúng cột
                'status'           => $faker->randomElement($statuses),
                'price'            => $price,
                'area'             => $faker->randomFloat(2, 25, 800), // m²
                'bedrooms'         => $faker->numberBetween(0, 6),
                'bathrooms'        => $faker->numberBetween(1, 5),
                'floors'           => $faker->numberBetween(1, 5),
                'address'          => $faker->randomElement($streetNames) . ' ' . $faker->numberBetween(1, 500) . ', ' . $location->district . ', ' . $location->city,
                'postal_code'      => str_pad($faker->numberBetween(10000, 99999), 5, '0', STR_PAD_LEFT),
                'latitude'         => $faker->randomFloat(8, $latMin, $latMax),
                'longitude'        => $faker->randomFloat(8, $lngMin, $lngMax),
                'year_built'       => $faker->optional(0.8)->numberBetween(1990, (int) date('Y')),
                'contact_id'       => $faker->randomElement($contactIds),
                'created_by'       => $createdBy,
                'updated_by'       => $faker->optional(0.5)->randomElement(array_merge($agentIds, [$adminId])),
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);

            // Ảnh BĐS (3-7 ảnh)
            $imgCount     = $faker->numberBetween(3, 7);
            $primaryIndex = $faker->numberBetween(1, $imgCount);
            $images       = [];
            for ($j = 1; $j <= $imgCount; $j++) {
                $images[] = [
                    'property_id' => $propertyId,
                    'image_path'  => "storage/properties/{$propertyId}/image_{$j}.jpg",
                    'image_name' => "image_{$j}.jpg",
                    'is_primary' => $j === $primaryIndex,
                    'sort_order' => $j,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('property_images')->insert($images);

            // Tính năng ngẫu nhiên (3-7)
            $pick      = $faker->randomElements($featureIds, $faker->numberBetween(3, 7));
            $pivotRows = [];
            foreach ($pick as $fid) {
                $pivotRows[] = [
                    'property_id' => $propertyId,
                    'feature_id'  => $fid,
                ];
            }
            DB::table('property_features')->insert($pivotRows);

            // Cập nhật progress bar
            if ($i % 50 == 0) {
                $bar->setMessage("Đã tạo {$i}/{$totalProperties} bất động sản");
            }
            $bar->advance();
        }

        $bar->setMessage('Hoàn thành!');
        $bar->finish();
        $this->command->newLine();
        $this->command->line("   ✓ Đã tạo {$totalProperties} bất động sản với đầy đủ hình ảnh và tính năng");
    }
}
