<?php

namespace App\Services;

use App\Models\NBAPlayers;
use App\Models\NBATeam;

class NBAPlayersService
{
    public function getPlayer(array $params)
    {
        $teams = NBAPlayers::query()
            ->with([
                'games' => function ($q) use ($params) {
                    $q->when(isset($params['opponentTeamId']), function ($games) use ($params) {
                        $games->whereHas('teamStats', function ($stat) use ($params) {
                            $stat->where('teamId', '=', NBATeam::find($params['opponentTeamId'])->nbaTeamId);
                        });
                    })
                        ->orderBy('date', 'desc');
                },
            ])
            ->where('playerId', '=', $params['playerId'])
            ->get()
            ->first();

        return $teams;
    }
}
