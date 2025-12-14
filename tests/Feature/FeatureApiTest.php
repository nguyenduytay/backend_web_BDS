<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeatureApiTest extends TestCase
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
    public function test_get_all_features()
    {
        Feature::create(['name' => 'Hồ bơi', 'icon' => 'pool']);
        Feature::create(['name' => 'Gym', 'icon' => 'gym']);

        $response = $this->getJson('/api/features/all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_get_feature_by_id()
    {
        $feature = Feature::create(['name' => 'Hồ bơi', 'icon' => 'pool']);

        $response = $this->getJson("/api/features/search/{$feature->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_admin_can_create_feature()
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'name' => 'Bãi đỗ xe',
            'icon' => 'parking'
        ];

        $response = $this->postJson('/api/features/create', $data);

        $response->assertStatus(201) // Create trả về 201
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_admin_can_update_feature()
    {
        Sanctum::actingAs($this->admin);

        $feature = Feature::create(['name' => 'Hồ bơi', 'icon' => 'pool']);

        $data = [
            'name' => 'Hồ bơi', // name là required
            'icon' => 'infinity-pool'
        ];

        $response = $this->putJson("/api/features/update/{$feature->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_admin_can_delete_feature()
    {
        Sanctum::actingAs($this->admin);

        $feature = Feature::create(['name' => 'Hồ bơi', 'icon' => 'pool']);

        $response = $this->deleteJson("/api/features/delete/{$feature->id}");

        $response->assertStatus(200);
    }
}
