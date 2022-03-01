<?php

namespace App\Http\Repositories;

use App\Models\Game;
use Illuminate\Support\Str;

class GameRepository
{
   /**
     * Get all games.
     *
     * @return App\Models\Game
     */
    public function all()
    {
        return Game::all();
    }

   /**
     * Get a games.
     *
     * @param String $uuid
     * @return App\Models\Game
     */
    public function find($uuid){

        return Game::where('uuid',$uuid)->first();
    }

    /**
     * Create a new game.
     *
     *
     * @return App\Models\Game
     */
    public function create(){
        return Game::create(
            [   'size' => Game::DEFAULT_SIZE,
                'uuid' => (string) Str::uuid()
            ]
        );
    }

   /**
     * update a game for a winner and status.
     *
     * @param String $uuid
     * @param String $winner
     * @param String $status
     * @return App\Models\Game
     */
    public function update($uuid, $winner, $status)
    {
        $game = $this->find($uuid);
        $game->winner = $winner;
        $game->status = $status;
        $game->save();
        return $game->fresh();
    }

}
