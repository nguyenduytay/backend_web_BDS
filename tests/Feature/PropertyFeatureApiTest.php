<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Feature;
use App\Models\Location;
use App\Models\PropertyType;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PropertyFeatureApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $property;
    protected $feature;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'role' => 'user',
            'password' => Hash::make('password'),
            'phone' => '0912345679'
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

        $this->property = Property::create([
            'title' => 'Test Property',
            'description' => 'Test Description',
            'location_id' => $location->id,
            'property_type_id' => $propertyType->id,
            'status' => 'available',
            'price' => 1000000000,
            'area' => 100,
            'address' => '123 Test Street',
            'contact_id' => $contact->id,
            'created_by' => $this->user->id
        ]);

        $this->feature = Feature::create([
            'name' => 'Hồ bơi',
            'icon' => 'pool'
        ]);
    }

    /** @test */
    public function test_get_all_property_features()
    {
        $response = $this->getJson("/api/properties/{$this->property->id}/features/all");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_user_can_add_feature_to_property()
    {
        Sanctum::actingAs($this->user);

        $data = [
            'feature_id' => $this->feature->id
        ];

        $response = $this->postJson("/api/properties/{$this->property->id}/features/create", $data);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_delete_feature_from_property()
    {
        Sanctum::actingAs($this->user);

        // Thêm feature trước
        \DB::table('property_features')->insert([
            'property_id' => $this->property->id,
            'feature_id' => $this->feature->id
        ]);

        $response = $this->deleteJson("/api/properties/{$this->property->id}/features/delete/{$this->feature->id}");

        $response->assertStatus(200);
    }
}
