<?php

namespace App\Models;

use App\Models\Turn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
    use  HasFactory;
    public const DEFAULT_SIZE = 3;

    protected  $fillable = ['uuid','size','status'];

    public function turns()
    {
        return $this->hasMany(Turn::class);
    }

    public function getCellsNumberAttribute()
    {
        return $this->size * $this->size;

    }
}
