<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;


    public function testUserCanMakeRegistration()
    {
        $registrationData = [
            'name' => 'Test user',
            'email' => 'test.user@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];
        $response = $this->post(route('api.v1.user_register'), $registrationData);
        $response->assertStatus(201);
        $response->assertJsonStructure(['status', 'message', 'data']);
        $response->assertJsonCount(2, 'data');
        $user = User::all()->first();
        $this->assertDatabaseHas('users', ['name' => 'Test user',
            'email' => 'test.user@gmail.com',]);
        $this->assertTrue(Hash::check($registrationData['password'], $user->password));


    }

    public function testUserCanLogIn()
    {
        User::factory()->create([
            'email' => 'test.user@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $response = $this->post(route('api.v1.user_login'), [
            'email' => 'test.user@gmail.com',
            'password' => '12345678'
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure(['status', 'message', 'data']);
        $response->assertJsonCount(2, 'data');
    }

    public function testUserCanDisconnect()
    {
        $user = User::factory()->create([
            'email' => 'test.user@gmail.com',
            'password' => Hash::make('12345678')
        ]);

        Sanctum::actingAs($user);
        $response = $this->post(route('api.v1.user_logout'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data']);
    }
}
