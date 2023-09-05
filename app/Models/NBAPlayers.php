<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NBAPlayers extends Model
{
    protected $table = 'players';
    protected $primaryKey = 'playerId';
    protected $fillable = [
        'firstName', 
        'familyName',
        'teamId',
    ];    

    public function team()
    {
        return $this->belongsTo(NBATeam::class, 'teamId', 'nbaTeamId');
    }

    public function games()
    {
        return $this->hasManyThrough(
            NBAGames::class,
            NBAPlayerStats::class,
            'playerId',
            'nbaGameId',
            'nbaPlayerId',
            'gameId'
        );
    }
}
