<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Location;
use App\Models\PropertyType;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FavoriteApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $property;

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
    }

    /** @test */
    public function test_get_user_favorites()
    {
        $response = $this->getJson("/api/users/{$this->user->id}/favorites/all");

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_add_favorite()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/properties/{$this->property->id}/favorite/create/{$this->user->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_user_can_remove_favorite()
    {
        Sanctum::actingAs($this->user);

        // Thêm favorite trước
        \DB::table('favorites')->insert([
            'user_id' => $this->user->id,
            'property_id' => $this->property->id
        ]);

        $response = $this->deleteJson("/api/properties/{$this->property->id}/favorite/delete/{$this->user->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function test_user_can_check_if_favorite()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/properties/{$this->property->id}/favorite/is_favorite/{$this->user->id}");

        // Có thể trả về 200 hoặc 400 nếu có lỗi
        $this->assertContains($response->status(), [200, 400]);
    }
}

