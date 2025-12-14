<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_can_register()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Secure123!@#', // Mật khẩu đủ mạnh, không chứa "password"
            'password_confirmation' => 'Secure123!@#',
            'phone' => '0912345678',
            'role' => 'user' // Role là bắt buộc
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(201) // Register trả về 201
                 ->assertJsonStructure([
                     'status',
                     'data' // data là user object, không có token
                 ]);
    }

    /** @test */
    public function test_user_can_login()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '0912345678'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' // data là token string, không phải object
                 ]);
    }

    /** @test */
    public function test_user_can_get_profile()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '0912345678'
        ]);

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /** @test */
    public function test_user_can_logout()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '0912345678'
        ]);

        // Tạo token và thêm vào header
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/auth/logout');

        $response->assertStatus(200);
    }
}
