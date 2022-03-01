<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Turn;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TurnTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_all_turns_and_got_401()
    {
        $uuid = Str::uuid();
        Game::factory()->create(['uuid' => $uuid]);
        $response = $this->getJson("/api/v1/games/$uuid/turns");
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_get_all_turns_and_got_200()
    {
        $uuid = Str::uuid();
        $game = Game::factory()->create(['uuid' => $uuid]);
        Turn::factory()->create(
            ['player_nr' => 1, 'location'=> 1, 'game_id' => $game->id],
        )
        ->create(
            ['player_nr' => 2, 'location'=> 2, 'game_id' => $game->id],
        );
        $response = $this->withoutMiddleware()->getJson("/api/v1/games/$uuid/turns");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2);

    }

    public function test_post_turn_and_got_401()
    {
        $uuid = Str::uuid();
        $game = Game::factory()->create(['uuid' => $uuid]);
        $payload = [
            'game_id' => $game->id,
            'player_nr' => 1, 'location'=> 1
        ];
        $response = $this->postJson("/api/v1/games/$uuid/turns", $payload);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_post_turn_and_got_201()
    {
        $uuid = Str::uuid();
        $game = Game::factory()->create(['uuid' => $uuid]);
        $payload = [
            'game_id' => $game->id,
            'player_nr' => 1, 'location'=> 1
        ];
        $response = $this->withoutMiddleware()->postJson("/api/v1/games/$uuid/turns", $payload);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_post_turn_and_got_422()
    {
        $uuid = Str::uuid();
        $game = Game::factory()->create(['uuid' => $uuid]);
        $payload = [
            'game_id' => $game->id,
            'player_nr' => 1, 'location'=> 1
        ];
        $response = $this->withoutMiddleware()->postJson("/api/v1/games/$uuid/turns", $payload);
        $response = $this->withoutMiddleware()->postJson("/api/v1/games/$uuid/turns", $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
