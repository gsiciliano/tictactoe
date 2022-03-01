<?php

namespace App\Http\Repositories;

use App\Models\Turn;

class TurnRepository
{
   /**
     * Get all turns from a game uuid.
     *
     * @param String $uuid
     * @return App\Models\Turn
     */
    public function get($uuid)
    {
        $turns = Turn::whereHas('game', function($query) use ($uuid)
        {
            $query->where('uuid',$uuid);
        })->get();

        return $turns;
    }

    /**
     * Create a turn.
     *
     * @param Array $turnData
     * @return App\Models\Turn
     */
    public function create($turnData)
    {
        return Turn::create($turnData);
    }
}
