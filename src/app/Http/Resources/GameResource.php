<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    private const HORIZONTAL = 'row';
    private const VERTICAL = 'col';
    private const DIAGONAL_RIGHT = 'diagL';
    private const DIAGONAL_LEFT = 'diagL';

    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $turns = $this->turns->all();
        $gameBoard = $this->getGameBoard($turns);
        return [
            "id" => $this->id,
            "uuid" => $this->uuid,
            "creation date" => Carbon::createFromTimeString($this->created_at)->format('d-m-Y H:i:s'),
            "status" => $this->status,
            "winner" => $this->winner,
            "board" => $gameBoard,

        ];
    }

    private function getGameBoard($turns)
    {
        $board = [];
        for ($index = 1; $index <= $this->cells_number; $index ++)
        {
            $turn_key= array_search($index, array_column($turns,'location'));
            $board[] = [
                'location' => $index,
                'player_nr' => $turn_key !== false ? $turns[$turn_key]['player_nr'] : null,
            ];
        }
        return $board;
    }


}
