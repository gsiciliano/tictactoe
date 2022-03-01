<?php

namespace App\Http\Services;

use App\Models\Game;

class GameService
{
    private const HORIZONTAL = 'row';
    private const VERTICAL = 'col';
    private const DIAGONAL_RIGHT = 'diagR';
    private const DIAGONAL_LEFT = 'diagL';

    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getWinner()
    {
        $turns = $this->game->turns->all();

        $players = data_get($turns,'*.player_nr');
        foreach ($players as $player_nr)
        {
            $player_turns = $this->getPlayerTurns($player_nr, $turns);
            if ($this->checkWinnerLinear($player_turns, self::HORIZONTAL)
            ||  $this->checkWinnerLinear($player_turns, self::VERTICAL)
            ||  $this->checkWinnerDiagonal($player_turns, self::DIAGONAL_LEFT)
            ||  $this->checkWinnerDiagonal($player_turns, self::DIAGONAL_RIGHT)
            )
            {
                return $player_nr;
            }
        }
        return null;

    }
    private function getPlayerTurns($player_nr, $turns)
    {
        return collect($turns)->filter(function($turn) use ($player_nr)
        {
            return $player_nr == $turn['player_nr'];
        });
    }
    private function checkWinnerLinear($player_turns, $direction)
    {
        for ($index = 1; $index <= $this->game->size; $index ++)
        {
            $count = 0;
            foreach($player_turns as $turn)
            {
                if ($this->getCellCoordinate($turn->location, $direction) == $index)
                {
                    $count ++;
                }
            }
            if ($count == $this->game->size){
                return true;
            }
        }
        return false;
    }
    private function checkWinnerDiagonal($player_turns, $direction)
    {
        switch ($direction)
        {
            case self::DIAGONAL_LEFT:   /** diagonal right is:  "/"  */
                $index_start_point = 1;
                $index_step_value = $this->game->size + 1;
                break;
            case self::DIAGONAL_RIGHT:  /** diagonal left  is:  "\"  */
                $index_start_point = $this->game->size;
                $index_step_value = $this->game->size - 1;
                break;
        }
        $count = 0;
        for ($index = $index_start_point; $index <= $this->game->cells_number; $index += $index_step_value)
        {
            foreach($player_turns as $turn)
            {
                if ($turn->location == $index){
                    $count ++;
                }
            }
            if ($count == $this->game->size){
                return true;
            }
        }
        return false;
    }
    private function getCellCoordinate($index, $direction)
    {
        $reminder = $index % $this->game->size;
        switch($direction)
        {
            case self::HORIZONTAL: return intdiv($index, $this->game->size) + ($reminder > 0 ? 1 :0);
            case self::VERTICAL:   return $reminder > 0 ? $reminder : $this->game->size;
            default:               return null;
        }
    }

    public function getStatus($winner)
    {
        if ($winner){
            return 'won';
        } else {
            if (count($this->game->turns->all()) == $this->game->cells_number)
            {
                return 'tie';
            }
        }
        return null;
    }
}
