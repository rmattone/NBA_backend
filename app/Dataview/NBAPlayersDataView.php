<?php

namespace App\DataView;

use App\Models\NBAGames;
use App\Models\NBATeam;
use Carbon\Carbon;

class NBAPlayersDataView
{
    public function getPlayer($player)
    {
        $gamesPlayed = $this->getGameStats($player->games, $player->nbaPlayerId);
        return [
            'playerId' => $player->playerId,
            'firstName' => $player->firstName,
            'familyName' => $player->familyName,
            'nbaPlayerId' => $player->nbaPlayerId,
            'team' => $player->team,
            'lastGames' => $gamesPlayed
        ];
    }

    public function getGameStats($games, $nbaPlayerId)
    {
        return $games->map(function ($game) use ($nbaPlayerId) {
            $actualGame = $game;
            $playerStats =  $actualGame->playerStats->where('playerId', '=', $nbaPlayerId)->first();
            $teamStats =  $actualGame->teamStats;
            return [
                'gameInfo' => [
                    'gameId' => $game->gameId,
                    'nbaGameId' => $game->nbaGameId,
                    'date' => Carbon::parse($game->date)->format('d/m/Y'),
                    'seasonType' => $game->seasonType,
                ],
                'teamStats' => $teamStats->map(function ($teamStat) {
                    $team = NBATeam::where('nbaTeamId', '=', $teamStat->teamId)->first();
                    return [
                        'teamId' => $team->teamId,
                        'nbaTeamId' => $team->nbaTeamId,
                        'name' => $team->city . ' ' . $team->name,
                        'tricode' => $team->tricode,
                        'slug' => $team->slug,
                        'fieldGoalsMade' => $teamStat->fieldGoalsMade,
                        'fieldGoalsAttempted' => $teamStat->fieldGoalsAttempted,
                        'fieldGoalsPercentage' => $teamStat->fieldGoalsPercentage,
                        'threePointersMade' => $teamStat->threePointersMade,
                        'threePointersAttempted' => $teamStat->threePointersAttempted,
                        'threePointersPercentage' => $teamStat->threePointersPercentage,
                        'freeThrowsMade' => $teamStat->freeThrowsMade,
                        'freeThrowsAttempted' => $teamStat->freeThrowsAttempted,
                        'freeThrowsPercentage' => $teamStat->freeThrowsPercentage,
                        'reboundsOffensive' => $teamStat->reboundsOffensive,
                        'reboundsDefensive' => $teamStat->reboundsDefensive,
                        'reboundsTotal' => $teamStat->reboundsTotal,
                        'assists' => $teamStat->assists,
                        'steals' => $teamStat->steals,
                        'blocks' => $teamStat->blocks,
                        'turnovers' => $teamStat->turnovers,
                        'foulsPersonal' => $teamStat->foulsPersonal,
                        'points' => $teamStat->points,
                        'plusMinusPoints' => $teamStat->plusMinusPoints,
                        'host' => $teamStat->host
                    ];
                }),
                'playerStats' => [
                    'minutes' => $playerStats->minutes,
                    'fieldGoalsMade' => $playerStats->fieldGoalsMade,
                    'fieldGoalsAttempted' => $playerStats->fieldGoalsAttempted,
                    'threePointersMade' => $playerStats->threePointersMade,
                    'threePointersAttempted' => $playerStats->threePointersAttempted,
                    'freeThrowsMade' => $playerStats->freeThrowsMade,
                    'freeThrowsAttempted' => $playerStats->freeThrowsAttempted,
                    'reboundsOffensive' => $playerStats->reboundsOffensive,
                    'reboundsDefensive' => $playerStats->reboundsDefensive,
                    'rebounds' => $playerStats->rebounds,
                    'assists' => $playerStats->assists,
                    'steals' => $playerStats->steals,
                    'blocks' => $playerStats->blocks,
                    'turnovers' => $playerStats->turnovers,
                    'foulsPersonal' => $playerStats->foulsPersonal,
                    'points' => $playerStats->points,
                    'plusMinusPoints' => $playerStats->plusMinusPoints,
                ]
            ];
        });
    }
}
