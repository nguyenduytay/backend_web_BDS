<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PropertyFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $propertyIds = DB::table('properties')->pluck('id')->all();
        $featureIds  = DB::table('features')->pluck('id')->all();

        foreach ($propertyIds as $pid) {
            shuffle($featureIds);
            $count = $faker->numberBetween(3, min(7, count($featureIds)));
            $pick  = array_slice($featureIds, 0, $count);

            foreach ($pick as $fid) {
                DB::table('property_features')->updateOrInsert(
                    [
                        'property_id' => $pid,
                        'feature_id'  => $fid,
                    ],
                    [] // không cần update gì thêm
                );
            }
        }
    }
}
