<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
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
    public function test_admin_can_get_all_users()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/users/index');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_user_cannot_get_all_users()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/users/index');

        $response->assertStatus(400); // API trả về 400 với message lỗi thay vì 403
    }

    /** @test */
    public function test_admin_can_create_user()
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'name' => 'New User',
            'email' => 'newuser@test.com',
            'password' => 'Secure123!@#', // Mật khẩu đủ mạnh, không chứa "password"
            'password_confirmation' => 'Secure123!@#',
            'phone' => '0912345680',
            'role' => 'user'
        ];

        $response = $this->postJson('/api/users/create', $data);

        $response->assertStatus(201) // Create trả về 201
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_user_can_get_own_profile()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/users/show?id=' . $this->user->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_user_can_update_own_profile()
    {
        Sanctum::actingAs($this->user);

        $data = [
            'name' => 'Updated Name',
            'phone' => '0912345690'
        ];

        $response = $this->putJson('/api/users/update?id=' . $this->user->id, $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    /** @test */
    public function test_admin_can_delete_user()
    {
        Sanctum::actingAs($this->admin);

        $newUser = User::create([
            'name' => 'To Delete',
            'email' => 'delete@test.com',
            'role' => 'user',
            'password' => Hash::make('password'),
            'phone' => '0912345691'
        ]);

        $response = $this->postJson('/api/users/delete?id=' . $newUser->id);

        $response->assertStatus(200);
    }
}
