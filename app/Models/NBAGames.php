<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NBAGames extends Model
{
    protected $table = 'games';
    protected $primaryKey = 'gameId';
    protected $fillable = [
        'date',
        'nbaGameId',
        'seasonType',
    ];

    public function teamStats()
    {
        return $this->hasMany(NBATeamStats::class, 'gameId', 'nbaGameId');
    }
    
    public function playerStats()
    {
        return $this->hasMany(NBAPlayerStats::class, 'gameId', 'nbaGameId');
    }
    
    public function playByPlay()
    {
        return $this->hasMany(NBAPlayByPlay::class, 'gameId', 'nbaGameId');
    }
}
