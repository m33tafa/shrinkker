<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * test create user validation required name field failed
     */
    public function test_create_user_validation_required_name_field_failed(): void
    {
        $response = $this->postJson('/api/auth/register',
         ['email' => 'sally@green.com','password', 'password' => 'saLLy23!-Gre90']);

        $response
        ->assertStatus(401)
        ->assertJson([
            "status" => false,
            "message" => "validation error",
            "errors" => [
                "name" => [
                    "The name field is required."
                ]
        ]
        ]);
    }

    /**
     * test create user validation required email field failed
     */
    public function test_create_user_validation_required_email_field_failed(): void
    {
        $response = $this->postJson('/api/auth/register',
         ['name' => 'Sally Green','password' => 'saLLy23!-Gre90']);

        $response
        ->assertStatus(401)
        ->assertJson([
            "status" => false,
            "message" => "validation error",
            "errors" => [
                "email" => [
                    "The email field is required."
                ]
        ]
        ]);
    }

    /**
     * test create user validation email valid address field failed
     */
    public function test_create_user_validation_email_field_valid_address_failed(): void
    {
        $response = $this->postJson('/api/auth/register',
         ['name' => 'Sally Green', 'email' => 'sallyG', 'password' => 'saLLy23!-Gre90']);

        $response
        ->assertStatus(401)
        ->assertJson([
            "status" => false,
            "message" => "validation error",
            "errors" => [
                "email" => [
                    "The email field must be a valid email address."
                ]
        ]
        ]);
    }

    /**
     * test create user validation required password field failed
     */
    public function test_create_user_validation_password_field_failed(): void
    {
        $response = $this->postJson('/api/auth/register',
         ['name' => 'Sally Green','email' => 'sally@green.com']);

        $response
        ->assertStatus(401)
        ->assertJson([
            "status" => false,
            "message" => "validation error",
            "errors" => [
                "password" => [
                    "The password field is required."
                ]
        ]
        ]);
    }

    /**
     * test create user all validations passed
     */
    public function test_create_user_all_validations_passed(): void
    {
        // use the factory to create a Faker\Generator instance
        $fakerFactory = Factory::create();
        $userName = $fakerFactory->name();
        $userEmail = $fakerFactory->email();
        $userPassword = $fakerFactory->password(6,20);

        $response = $this->postJson('/api/auth/register',
         ['name' => $userName,'email' => $userEmail,'password' => $userPassword]);
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Shrinkker User Created Successfully',
            ]);
    }

    /**
     * test create user all validations passed but email already taken
     */
    public function test_create_user_all_validations_passed_but_email_already_taken(): void
    {
        // use the factory to create an user
        User::factory()->create([
            'name' => 'Sully Boyle',
            'email' => 'sully@boyle.com'
        ]);

        $response = $this->postJson('/api/auth/register',
         ['name' => 'Harold Boyle','email' => 'sully@boyle.com','password' => 'bpyö(883f!ia']);
        $response
            ->assertStatus(401)
            ->assertJson([
                "status" => false,
                "message" => "validation error",
                "errors" => [
                    "email" => [
                        "The email has already been taken."
                    ]
                ]
            ]);
    }


    /**
     * test login user was successful
     */
    public function test_login_user_success(): void
    {
        // use the factory to create an user
        User::factory()->create([
            'name' => 'Richard Sutt',
            'email' => 'risutt76@mysh.edu',
            'password' => Hash::make('sA//-Gre90!sf')
        ]);
        $response = $this->postJson('/api/auth/login',
         ['email' => 'risutt76@mysh.edu','password' => 'sA//-Gre90!sf']);
        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => true,
            'message' => 'Shrinkker User Logged In Successfully'
        ]);
    }

    /**
     * test login user failed
     */
    public function test_login_user_failed(): void
    {
        // use the factory to create an user
        User::factory()->create([
            'name' => 'Dan Gunner',
            'email' => 'dangunner@mysh.edu',
            'password' => Hash::make('sA//-Gre90!sf')
        ]);
        $response = $this->postJson('/api/auth/login',
         ['email' => 'sally@green.com','password' => 'sLLy23!A//-Gre90']);
        $response
        ->assertStatus(401)
        ->assertJson([
            'status' => false,
            'message' => 'Email & Password does not match with our Shrinkk user record!'        ]);
    }
}
