<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    //Register
    public function  test_register_success(){
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => 'securepassword123',
        ]);

        $response->assertStatus(201)->assertJsonStructure(['message', 'accessToken']);
    }

    public function test_register_fail()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'testuser',
            'password' => 'pass',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'password']);
    }

   //Login
    public function  test_login_success(){
        $user = User::factory()->create([
            'password' => bcrypt('securepassword123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'securepassword123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
    }

     // Logout
     public function  test_login_fail(){
        $user = User::factory()->create([
            'password' => Hash::make('securepassword123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'The provided credentials are incorrect.']);
    }

   //Logout
    public function  test_logout(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)->assertJson(['message' => 'User logged out successfully']);
    }

    //Reset Password
    public function  test_tresetPassword_success(){
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123'),
        ]);

        $response = $this->postJson('/api/resetpassword', [
            'email' => $user->email,
            'password' => 'newpassword456',
        ]);

        

        $response->assertStatus(200)->assertJson(['message' => 'Password updated successfully.']);
    }

    public function  test_resetPassword_fail_short_pwd(){
        $user = User::factory()->create();

        $response = $this->postJson('/api/resetpassword', [
            'email' => $user->email,
            'password' => 'short',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

   
    public function  test_resetPassword_fail_invalid_email(){
        
        $response = $this->postJson('/api/resetpassword', [
            'email' => 'nonexistent@example.com',
            'password' => 'newpassword456', // New password
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function  test_resetPassword_fail_empty_request(){
        
        $response = $this->postJson('/api/resetpassword', []);
        $response->assertStatus(422)->assertJsonValidationErrors(['email', 'password']);
    }

    public function  test_resetPassword_fail_no_email(){
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);
        $response = $this->postJson('/api/resetpassword', [
            'password' => 'newpassword456', // Missing email
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }
}


