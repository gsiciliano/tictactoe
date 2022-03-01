<?php

namespace App\Rules;

use App\Models\Game;
use Illuminate\Contracts\Validation\Rule;

class ItsNotPlayerTurnRule implements Rule
{
    private $game;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $lastTurn = $this->game->turns->last();
        return empty($lastTurn) || $lastTurn->player_nr != $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Is not :attribute :input turn, yet';
    }
}
