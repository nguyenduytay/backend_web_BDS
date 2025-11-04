<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $now = Carbon::now();

        $locationIds = DB::table('locations')->pluck('id')->all();
        $contactIds  = DB::table('contacts')->pluck('id')->all();
        $featureIds  = DB::table('features')->pluck('id')->all();
        $types       = DB::table('property_types')->pluck('type', 'id')->toArray(); // [id => type]
        $agentIds    = DB::table('users')->where('role', 'agent')->pluck('id')->all();
        $adminId     = DB::table('users')->where('role', 'admin')->value('id');

        // Phạm vi toạ độ Việt Nam (xấp xỉ)
        $latMin = 8.179;
        $latMax = 23.392;
        $lngMin = 102.144;
        $lngMax = 109.469;

        $statuses = ['available', 'sold', 'rented', 'pending'];

        for ($i=1; $i<=300; $i++) {
            // Lấy random id + type
            $typeId = $faker->randomElement(array_keys($types));
            $type   = $types[$typeId];

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
                'title'            => ucfirst($faker->words(3, true)).' - '.$faker->city(),
                'description'      => $faker->optional(0.8)->paragraphs(3, true),
                'location_id'      => $faker->randomElement($locationIds),
                'property_type_id' => $typeId, // ✅ đúng cột
                'status'           => $faker->randomElement($statuses),
                'price'            => $price,
                'area'             => $faker->randomFloat(2, 25, 800), // m²
                'bedrooms'         => $faker->numberBetween(0, 6),
                'bathrooms'        => $faker->numberBetween(1, 5),
                'floors'           => $faker->numberBetween(1, 5),
                'address'          => $faker->streetAddress(),
                'postal_code'      => $faker->postcode(),
                'latitude'         => $faker->randomFloat(8, $latMin, $latMax),
                'longitude'        => $faker->randomFloat(8, $lngMin, $lngMax),
                'year_built'       => $faker->optional(0.8)->numberBetween(1990, (int)date('Y')),
                'contact_id'       => $faker->randomElement($contactIds),
                'created_by'       => $createdBy,
                'updated_by'       => $faker->optional(0.5)->randomElement(array_merge($agentIds, [$adminId])),
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);

            // Ảnh BĐS (3-7 ảnh)
            $imgCount = $faker->numberBetween(3, 7);
            $primaryIndex = $faker->numberBetween(1, $imgCount);
            $images = [];
            for ($j=1; $j<=$imgCount; $j++) {
                $images[] = [
                    'property_id' => $propertyId,
                    'image_path'  => "storage/properties/{$propertyId}/image_{$j}.jpg",
                    'image_name'  => "image_{$j}.jpg",
                    'is_primary'  => $j === $primaryIndex,
                    'sort_order'  => $j,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
            DB::table('property_images')->insert($images);

            // Tính năng ngẫu nhiên (3-7)
            $pick = $faker->randomElements($featureIds, $faker->numberBetween(3, 7));
            $pivotRows = [];
            foreach ($pick as $fid) {
                $pivotRows[] = [
                    'property_id' => $propertyId,
                    'feature_id'  => $fid,
                ];
            }
            DB::table('property_features')->insert($pivotRows);
        }
    }
}
