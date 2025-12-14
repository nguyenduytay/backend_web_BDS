<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Location;
use App\Models\PropertyType;
use App\Models\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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
            'title' => 'Căn hộ đẹp tại Ba Đình',
            'description' => 'Mô tả căn hộ',
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
    public function test_search_properties()
    {
        $response = $this->getJson('/api/search/properties?q=Căn hộ');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_filter_properties()
    {
        $response = $this->getJson('/api/search/filter?price_min=500000000&price_max=2000000000');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_autocomplete_search()
    {
        $response = $this->getJson('/api/search/autocomplete?q=Căn');

        $response->assertStatus(200);
    }

    /** @test */
    public function test_nearby_search()
    {
        $response = $this->getJson('/api/search/nearby?latitude=21.0285&longitude=105.8542&radius=5');

        $response->assertStatus(200);
    }
}

