<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NBAPlayerStats extends Model
{
    protected $table = 'playerStats';
    protected $primaryKey = 'playerStatsId';
    protected $fillable = [
        'playerId', 
        'gameId',
    ];    

    public function player()
    {
        return $this->belongsTo(NBAPlayers::class, 'playerId', 'nbaPlayerId');
    }
    
    public function gameStats()
    {
        return $this->hasMany(NBATeamStats::class, 'playerId', 'nbaPlayerId');
    }
}
