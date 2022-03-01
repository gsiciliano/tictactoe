<?php

namespace App\Http\Requests;

use App\Rules\ItsNotPlayerTurnRule;
use App\Rules\LocationAlreadyFilledRule;
use App\Http\Repositories\GameRepository;
use Illuminate\Foundation\Http\FormRequest;

class TurnRequest extends FormRequest
{
    protected $gameRepository;
    protected $game;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    protected function prepareForValidation()
    {
        $this->game = $this->gameRepository->find($this->route('uuid'));
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->game;
    }

    /**
    *     @OA\Schema(
    *         schema="turnRequest",
    *         type="object",
    *         @OA\Property(
    *             property="player_nr",
    *             type="string",
    *             example = "1"
    *         ),
    *         @OA\Property(
    *             property="location",
    *              type="string",
    *              example="1"
    *         ),
    *     )
    */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location' => ['required','integer','gte:1','lte:9', new LocationAlreadyFilledRule($this->game)],
            'player_nr' => ['required','integer','gte:1','lte:2', new ItsNotPlayerTurnRule($this->game)]
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->game->status) {
                $validator->errors()->add('game', 'Game over!');
            }
        });
    }
    public function validated()
    {
        return array_merge(parent::validated(),['game_id' => $this->game->id]);
    }
}
