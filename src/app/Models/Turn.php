<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
    use HasFactory;

    protected $fillable =  ['game_id','location','player_nr'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

}
