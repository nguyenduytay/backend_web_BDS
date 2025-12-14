<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Property;
use App\Models\Location;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

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

        $this->user = User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'role' => 'user',
            'password' => Hash::make('password'),
            'phone' => '0912345679'
        ]);
    }

    /** @test */
    public function test_admin_can_get_all_contacts()
    {
        Sanctum::actingAs($this->admin);

        Contact::create([
            'name' => 'Test Contact',
            'phone' => '0912345678',
            'email' => 'contact@test.com'
        ]);

        $response = $this->getJson('/api/contacts/all');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_search_contacts()
    {
        Sanctum::actingAs($this->admin);

        $contact = Contact::create([
            'name' => 'Test Contact',
            'phone' => '0912345678',
            'email' => 'contact@test.com'
        ]);

        $response = $this->getJson("/api/contacts/search?id={$contact->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_create_contact()
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'name' => 'New Contact',
            'phone' => '0912345680',
            'email' => 'newcontact@test.com'
        ];

        $response = $this->postJson('/api/contacts/create', $data);

        $response->assertStatus(201) // Create trả về 201
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_user_can_get_seller_contact()
    {
        Sanctum::actingAs($this->user);

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
            'name' => 'Seller Contact',
            'phone' => '0912345678',
            'email' => 'seller@test.com'
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

        $response = $this->getJson("/api/contacts/seller/{$property->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }
}
