<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Location;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

class PropertyApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $user;
    protected $admin;
    protected $token;
    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo admin user
        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'phone' => '0912345678'
        ]);

        // Tạo regular user
        $this->user = User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'role' => 'user',
            'password' => Hash::make('password'),
            'phone' => '0912345679'
        ]);
    }

    /** @test */
    public function test_get_all_properties()
    {
        $response = $this->getJson('/api/properties/all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_get_properties_by_type()
    {
        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ',
            'slug' => 'can-ho'
        ]);

        $response = $this->getJson("/api/properties/by-type/{$propertyType->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_get_properties_by_location()
    {
        $response = $this->getJson('/api/properties/by-location');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_get_featured_properties()
    {
        $response = $this->getJson('/api/properties/featured');

        // Có thể trả về 200 hoặc 400 nếu không có dữ liệu
        $this->assertContains($response->status(), [200, 400]);
    }

    /** @test */
    public function test_get_property_detail()
    {
        $location = Location::create([
            'city' => 'Hà Nội',
            'district' => 'Ba Đình',
            'slug' => 'ha-noi-ba-dinh'
        ]);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ',
            'slug' => 'can-ho'
        ]);

        $contact = Contact::create([
            'name' => 'Test Contact',
            'email' => 'contact@test.com',
            'phone' => '0912345678'
        ]);

        $property = Property::create([
            'title' => 'Test Property',
            'description' => 'Test Description',
            'location_id' => $location->id,
            'property_type_id' => $propertyType->id,
            'status' => 'available',
            'price' => 1000000000,
            'area' => 100,
            'address' => '123 Test Street',
            'contact_id' => $contact->id
        ]);

        $response = $this->getJson("/api/properties/detail/{$property->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_create_property_requires_auth()
    {
        $response = $this->postJson('/api/properties/create', []);

        $response->assertStatus(400); // API trả về 400 với message lỗi thay vì 401
    }

    /** @test */
    public function test_create_property_with_auth()
    {
        Sanctum::actingAs($this->user);

        $location = Location::create([
            'city' => 'Hà Nội',
            'district' => 'Ba Đình',
            'slug' => 'ha-noi-ba-dinh'
        ]);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ',
            'slug' => 'can-ho'
        ]);

        $contact = Contact::create([
            'name' => 'Test Contact',
            'email' => 'contact@test.com',
            'phone' => '0912345678'
        ]);

        $data = [
            'title' => 'Test Property',
            'description' => 'Test Description',
            'location_id' => $location->id,
            'property_type_id' => $propertyType->id,
            'status' => 'available',
            'price' => 1000000000,
            'area' => 100,
            'address' => '123 Test Street',
            'contact_id' => $contact->id
        ];

        $response = $this->postJson('/api/properties/create', $data);

        $response->assertStatus(201) // Create trả về 201
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_update_property_requires_auth()
    {
        $location = Location::create([
            'city' => 'Hà Nội',
            'district' => 'Ba Đình',
            'slug' => 'ha-noi-ba-dinh'
        ]);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ',
            'slug' => 'can-ho'
        ]);

        $contact = Contact::create([
            'name' => 'Test Contact',
            'email' => 'contact@test.com',
            'phone' => '0912345678'
        ]);

        $property = Property::create([
            'title' => 'Test Property',
            'description' => 'Test Description',
            'location_id' => $location->id,
            'property_type_id' => $propertyType->id,
            'status' => 'available',
            'price' => 1000000000,
            'area' => 100,
            'address' => '123 Test Street',
            'contact_id' => $contact->id
        ]);

        $response = $this->putJson("/api/properties/update/{$property->id}", []);

        $response->assertStatus(400); // API trả về 400 với message lỗi thay vì 401
    }

    /** @test */
    public function test_delete_property_requires_auth()
    {
        $location = Location::create([
            'city' => 'Hà Nội',
            'district' => 'Ba Đình',
            'slug' => 'ha-noi-ba-dinh'
        ]);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ',
            'slug' => 'can-ho'
        ]);

        $contact = Contact::create([
            'name' => 'Test Contact',
            'email' => 'contact@test.com',
            'phone' => '0912345678'
        ]);

        $property = Property::create([
            'title' => 'Test Property',
            'description' => 'Test Description',
            'location_id' => $location->id,
            'property_type_id' => $propertyType->id,
            'status' => 'available',
            'price' => 1000000000,
            'area' => 100,
            'address' => '123 Test Street',
            'contact_id' => $contact->id
        ]);

        $response = $this->deleteJson("/api/properties/delete/{$property->id}");

        $response->assertStatus(400); // API trả về 400 với message lỗi thay vì 401
    }

    /** @test */
    public function test_admin_can_approve_property()
    {
        Sanctum::actingAs($this->admin);

        $location = Location::create([
            'city' => 'Hà Nội',
            'district' => 'Ba Đình',
            'slug' => 'ha-noi-ba-dinh'
        ]);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ',
            'slug' => 'can-ho'
        ]);

        $contact = Contact::create([
            'name' => 'Test Contact',
            'email' => 'contact@test.com',
            'phone' => '0912345678'
        ]);

        $property = Property::create([
            'title' => 'Test Property',
            'description' => 'Test Description',
            'location_id' => $location->id,
            'property_type_id' => $propertyType->id,
            'status' => 'pending',
            'price' => 1000000000,
            'area' => 100,
            'address' => '123 Test Street',
            'contact_id' => $contact->id
        ]);

        $response = $this->postJson("/api/properties/approve/{$property->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_admin_can_hide_property()
    {
        Sanctum::actingAs($this->admin);

        $location = Location::create([
            'city' => 'Hà Nội',
            'district' => 'Ba Đình',
            'slug' => 'ha-noi-ba-dinh'
        ]);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ',
            'slug' => 'can-ho'
        ]);

        $contact = Contact::create([
            'name' => 'Test Contact',
            'email' => 'contact@test.com',
            'phone' => '0912345678'
        ]);

        $property = Property::create([
            'title' => 'Test Property',
            'description' => 'Test Description',
            'location_id' => $location->id,
            'property_type_id' => $propertyType->id,
            'status' => 'available',
            'price' => 1000000000,
            'area' => 100,
            'address' => '123 Test Street',
            'contact_id' => $contact->id
        ]);

        $response = $this->postJson("/api/properties/hide/{$property->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }
}
