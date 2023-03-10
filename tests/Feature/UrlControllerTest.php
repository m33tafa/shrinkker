<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{

    /**
     * test index - current authenticated user has no shrinkked urls
     */
    public function test_index_user_has_no_shrinkked_urls(): void
    {
        // create and authenticate test user
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->get('/api/url/shrinkk/list');
        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => false,
            'message' => 'Shrinkked URLs not found!'
        ]);
    }

    /**
     * test index - current authenticated user has shrinkked urls
     */
    public function test_index_user_has_shrinkked_urls(): void
    {
        // create and authenticate test user
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        // create 15 shrinkked URLs for the test user
        Url::factory(15)->create();

        $response = $this->get('/api/url/shrinkk/list');
        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => true,
            'message' => 'Shrinkked URLs List'
        ]);
    }

    /**
     * test create - create an shrinkked url
     */
    public function test_create_shrinkked_url(): void
    {
        // create and authenticate test user
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        // use the factory to create a Faker\Generator instance
        $fakerFactory = Factory::create();

        $response = $this->postJson('/api/url/shrinkk/create',
        ['url' => $fakerFactory->url()]);
        $response
        ->assertStatus(200)
        ->assertJson([
            "status" => true,
            "message" => "Given Url shrinkked successfully"
        ]);
    }

    /**
     * test delete - delete an shrinkked url
     */
    public function test_delete_shrinkked_url(): void
    {
        // create and authenticate test user
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        // create a random code
        $code = Str::random(8);

        // create an shrinkked URL for the test user
        Url::factory()->create([
            'code' => $code,
        ]);

        $response = $this->deleteJson('/api/url/shrinkk/delete/'.$code);
        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => true,
            'message' => 'Shrinkked URL with the code '.$code.' deleted'
        ]);
    }
}
