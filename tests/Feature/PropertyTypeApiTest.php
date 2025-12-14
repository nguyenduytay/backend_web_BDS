<?php

namespace Tests\Feature;

use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PropertyTypeApiTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'phone' => '0912345678'
        ]);
    }

    /** @test */
    public function test_get_all_property_types()
    {
        PropertyType::create(['type' => 'Căn hộ', 'name' => 'Căn hộ']);
        PropertyType::create(['type' => 'Nhà phố', 'name' => 'Nhà phố']);

        $response = $this->getJson('/api/property_types/all');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_search_property_type_by_type()
    {
        PropertyType::create(['type' => 'Căn hộ', 'name' => 'Căn hộ']);

        $response = $this->getJson('/api/property_types/search_type?type=Căn hộ');

        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_create_property_type()
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'type' => 'Biệt thự',
            'name' => 'Biệt thự'
        ];

        $response = $this->postJson('/api/property_types/create', $data);

        $response->assertStatus(201) // Create trả về 201
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_update_property_type()
    {
        Sanctum::actingAs($this->admin);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ'
        ]);

        $data = [
            'type' => 'Căn hộ', // type là required
            'name' => 'Căn hộ cao cấp'
        ];

        $response = $this->putJson("/api/property_types/update/{$propertyType->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_delete_property_type()
    {
        Sanctum::actingAs($this->admin);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ'
        ]);

        $response = $this->deleteJson("/api/property_types/delete/{$propertyType->id}");

        $response->assertStatus(200);
    }
}
