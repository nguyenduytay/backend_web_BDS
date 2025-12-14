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

    /** @test */
    public function test_search_location_by_city()
    {
        Location::create(['city' => 'Hà Nội', 'district' => 'Ba Đình', 'slug' => 'ha-noi-ba-dinh']);

        $response = $this->getJson('/api/locations/search_city?city=Hà Nội');

        $response->assertStatus(200);
    }

    /** @test */
    public function test_get_location_by_id()
    {
        $location = Location::create(['city' => 'Hà Nội', 'district' => 'Ba Đình', 'slug' => 'ha-noi-ba-dinh']);

        $response = $this->getJson("/api/locations/search/{$location->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_create_location()
    {
        $admin = \App\Models\User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'phone' => '0912345678'
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($admin);

        $data = [
            'city' => 'Đà Nẵng',
            'district' => 'Hải Châu',
            'slug' => 'da-nang-hai-chau'
        ];

        $response = $this->postJson('/api/locations/create', $data);

        $response->assertStatus(201) // Create trả về 201
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_update_location()
    {
        $admin = \App\Models\User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'phone' => '0912345678'
        ]);

        \Laravel\Sanctum\Sanctum::actingAs($admin);

        $location = Location::create(['city' => 'Hà Nội', 'district' => 'Ba Đình', 'slug' => 'ha-noi-ba-dinh']);

        $data = [
            'id' => $location->id,
            'city' => 'Hà Nội',
            'district' => 'Hoàn Kiếm',
            'slug' => 'ha-noi-hoan-kiem'
        ];

        $response = $this->putJson('/api/locations/update', $data);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }
}
