<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GameTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_all_games_and_got_401()
    {
        $response = $this->getJson('/api/v1/games');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_get_all_games_and_got_200()
    {
        Game::factory()->count(3)->create();
        $response = $this->withoutMiddleware()->getJson('/api/v1/games');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
    }

    public function test_get_a_games_and_got_401()
    {
        $uuid = Str::uuid();
        Game::factory()->create(['uuid' => $uuid]);
        $response = $this->getJson('/api/v1/games/'.$uuid);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_get_a_games_and_got_404()
    {
        $uuid = Str::uuid();
        $response = $this->withoutMiddleware()->getJson('/api/v1/games/'.$uuid);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_get_a_games_and_got_200()
    {
        $uuid = Str::uuid();
        Game::factory()->create(['uuid' => $uuid]);
        $response = $this->withoutMiddleware()->getJson('/api/v1/games/'.$uuid);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_post_a_games_and_got_401()
    {
        $response = $this->postJson('/api/v1/games');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_post_a_games_and_got_201()
    {
        $response = $this->withoutMiddleware()->postJson('/api/v1/games');
        $response->assertStatus(Response::HTTP_CREATED);
    }
}
