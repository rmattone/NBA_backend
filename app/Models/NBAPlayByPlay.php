<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NBAPlayByPlay extends Model
{
    protected $table = 'playByPlay';
    protected $primaryKey = 'playByPlayId';

    public function game()
    {
        return $this->belongsTo(NBATeam::class, 'gameId', 'nbaGameId')->limit(10);
    }
}
