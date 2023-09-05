<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NBATeam extends Model
{
    protected $table = 'teams';
    protected $primaryKey = 'teamId';
    protected $fillable = [
        'name',
        'city',
        'tricode',
        'slug',
        'nbaTeamId',
    ];

    public function players()
    {
        return $this->hasMany(NBAPlayers::class, 'teamId', 'nbaTeamId');
    }

    public function games()
    {
        return $this->hasManyThrough(
            NBAGames::class,
            NBATeamStats::class,
            'teamId',
            'nbaGameId',
            'nbaTeamId',
            'gameId'
        );
    }

    public function teamStats()
    {
        return $this->hasMany(NBATeamStats::class, 'teamId', 'nbaTeamId');
    }
}
