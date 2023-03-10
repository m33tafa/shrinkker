<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthControllerTest extends TestCase
{
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
        $response = $this->postJson('/api/auth/register',
         ['name' => 'Sally Green','email' => 'sally@green.com','password' => 'saLLy23!-Gre90']);

         if(!$this->assertDatabaseHas('users', [
            'email' => 'sally@green.com',
        ]))
        {
            $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Shrinkker User Created Successfully',
            ]);
        }else
        {
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
    }


    /**
     * test login user was successful
     */
    public function test_login_user_success(): void
    {
        $response = $this->postJson('/api/auth/login',
         ['email' => 'sally@green.com','password' => 'saLLy23!-Gre90']);
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
        $response = $this->postJson('/api/auth/login',
         ['email' => 'sally@green.com','password' => 'saLLy23!A//-Gre90']);
        $response
        ->assertStatus(401)
        ->assertJson([
            'status' => false,
            'message' => 'Email & Password does not match with our Shrinkk user record!'        ]);
    }
}
