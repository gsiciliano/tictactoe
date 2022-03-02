<?php

namespace App\Rules;

use App\Models\Game;
use Illuminate\Contracts\Validation\Rule;

class LocationAlreadyFilledRule implements Rule
{
    protected $game;
    /**
     * Create a new rule instance.
     *
     * @param App\Models\Game $game
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
        $location = $this->game->turns->where('location',$value)->first();
        return empty($location);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Location :input is already taken.';
    }
}
