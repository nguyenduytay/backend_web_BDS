<?php

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_get_all_locations()
    {
        Location::create(['city' => 'Hà Nội', 'district' => 'Ba Đình', 'slug' => 'ha-noi-ba-dinh']);
        Location::create(['city' => 'Hà Nội', 'district' => 'Hoàn Kiếm', 'slug' => 'ha-noi-hoan-kiem']);
        Location::create(['city' => 'Hồ Chí Minh', 'district' => 'Quận 1', 'slug' => 'ho-chi-minh-quan-1']);

        $response = $this->getJson('/api/locations/all');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_get_cities()
    {
        Location::create(['city' => 'Hà Nội', 'district' => 'Ba Đình', 'slug' => 'ha-noi-ba-dinh']);
        Location::create(['city' => 'Hồ Chí Minh', 'district' => 'Quận 1', 'slug' => 'ho-chi-minh-quan-1']);

        $response = $this->getJson('/api/locations/cities');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_get_districts_by_city()
    {
        Location::create([
            'city' => 'Hà Nội',
            'district' => 'Ba Đình',
            'slug' => 'ha-noi-ba-dinh'
        ]);

        $response = $this->getJson('/api/locations/cities/Hà Nội/districts');

        $response->assertStatus(200);
    }
}

