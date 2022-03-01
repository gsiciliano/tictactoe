<?php

namespace App\Http\Repositories;

use App\Models\Game;
use App\Models\Turn;
use App\Http\Resources\TurnResource;

class TurnRepository
{
   /**
     * Get all turns from a game uuid.
     *
     * @param String uuid
     * @return App\Http\Resources\TurnResource collection
     */
    public function get($uuid)
    {
        $turns = Turn::whereHas('game', function($query) use ($uuid)
        {
            $query->where('uuid',$uuid);
        })->get();

        return TurnResource::collection($turns);
    }

    /**
     * Create a turn.
     *
     *
     * @return App\Models\Turn turn
     */
    public function create($turnData)
    {
        return Turn::create($turnData);
    }
}
