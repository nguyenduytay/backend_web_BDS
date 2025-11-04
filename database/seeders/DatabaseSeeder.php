<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(class: UserSeeder::class);
        $this->call(class: LocationSeeder::class);
        $this->call(class: PropertyTypeSeeder::class);
        $this->call(class: FeatureSeeder::class);
        $this->call(class: ContactSeeder::class);
        $this->call(class: PropertySeeder::class);
        $this->call(class: PropertyImageSeeder::class);
        $this->call(class: PropertyFeatureSeeder::class);
        $this->call(class: FavoritesSeeder::class);
    }
}
