<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NBATeamStats extends Model
{
    protected $table = 'teamStats';
    protected $primaryKey = 'teamStatsId';
    protected $fillable = [
        'teamId',
        'gameId',
    ];

    public function team()
    {
        return $this->hasOne(NBATeam::class, 'nbaTeamId', 'teamId');
    }

    public function game()
    {
        return $this->belongsTo(NBAGames::class, 'gameId', 'nbaGameId');
    }
}
