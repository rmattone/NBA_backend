<?php

namespace App\Services;

use App\Models\NBAGames;
use App\Models\NBATeam;
use App\Models\NBATeamStats;

class NBATeamsService
{
    public function listTeams(array $params)
    {

        $teams = NBATeam::query()
            ->when(isset($params['playerName']), function ($query) use ($params) {
                $query->whereHas('players', function ($q) use ($params) {
                    $q->where('firstName', 'like', '%' . $params['playerName'] . '%')
                        ->orWhere('familyName', 'like', '%' . $params['playerName'] . '%');
                })->orderBy('familyName');
            })
            ->orderBy('city')
            ->get();

        return $teams;
    }

    public function games(NBATeam $team, array $params = [])
    {
        $teamStats = NBAGames::query()
            ->whereHas('teamStats', function ($query) use ($team) {
                $query->where('teamId', '=', $team->nbaTeamId);
            })
            ->with([
                'teamStats'
            ])
            ->get();
        return $teamStats;
    }

    public function firstAttempt(NBATeam $team, array $params = [])
    {
        $teamStats = NBAGames::query()
            ->whereHas('teamStats', function ($query) use ($team) {
                $query->where('teamId', '=', $team->nbaTeamId);
            })
            ->with([
                'playByPlay' => function ($query) {
                    $query->whereNotNull('score')
                        ->orWhere('visitorDescription', 'like', 'MISS%')
                        ->orWhere('homeDescription', 'like', 'MISS%')
                        ->limit(10);
                }
            ])
            ->get();
        return $teamStats;
    }
}
