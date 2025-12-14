<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\Location;
use App\Models\PropertyType;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardApiTest extends TestCase
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

        $location = Location::create([
            'city' => 'Hà Nội',
            'district' => 'Ba Đình',
            'slug' => 'ha-noi-ba-dinh'
        ]);

        $propertyType = PropertyType::create([
            'type' => 'Căn hộ',
            'name' => 'Căn hộ'
        ]);

        $contact = Contact::create([
            'name' => 'Test Contact',
            'phone' => '0912345678',
            'email' => 'contact@test.com'
        ]);

        Property::create([
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
    }

    /** @test */
    public function test_admin_can_get_stats()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/dashboard/stats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_get_property_stats()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/dashboard/property_stats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_get_user_stats()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/dashboard/user_stats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_get_recent_properties()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/dashboard/recent_properties');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_get_recent_users()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/dashboard/recent_users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_user_cannot_access_dashboard()
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'role' => 'user',
            'password' => Hash::make('password'),
            'phone' => '0912345679'
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/admin/dashboard/stats');

        $response->assertStatus(400); // API trả về 400 với message lỗi thay vì 403
    }
}
