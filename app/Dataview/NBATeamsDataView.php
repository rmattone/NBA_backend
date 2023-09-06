<?php

namespace App\DataView;

use App\Helpers\PaginatorHelper;
use App\Models\NBATeam;
use Carbon\Carbon;

class NBATeamsDataView
{
    private $paginatorHelper;

    public function __construct(
        PaginatorHelper $paginatorHelper
    ) {
        $this->paginatorHelper = $paginatorHelper;
    }


    public function listTeam($team)
    {
        return [
            'teamId' => $team->teamId,
            'name' => $team->city . ' ' . $team->name,
            'tricode' => $team->tricode,
            'slug' => $team->slug,
            'nbaTeamId' => $team->nbaTeamId,
            'color' => $team->color
        ];
    }

    public function listTeamPlayers($team)
    {
        return [
            'players' => $team->players->map(function ($player) {
                return [
                    'playerId' => $player->playerId,
                    'nbaPlayerId' => $player->nbaPlayerId,
                    'firstName' => $player->firstName,
                    'familyName' => $player->familyName
                ];
            })->sortBy('familyName')
        ];
    }

    public function teamStats($teamStats)
    {
        return $teamStats->map(function ($team) {
            return [
                'teamId' => $team->teamId,
                'fieldGoalsMade' => $team->fieldGoalsMade,
                'fieldGoalsAttempted' => $team->fieldGoalsAttempted,
                'fieldGoalsPercentage' => $team->fieldGoalsPercentage,
                'threePointersMade' => $team->threePointersMade,
                'threePointersAttempted' => $team->threePointersAttempted,
                'threePointersPercentage' => $team->threePointersPercentage,
                'freeThrowsMade' => $team->freeThrowsMade,
                'freeThrowsAttempted' => $team->freeThrowsAttempted,
                'freeThrowsPercentage' => $team->freeThrowsPercentage,
                'reboundsOffensive' => $team->reboundsOffensive,
                'reboundsDefensive' => $team->reboundsDefensive,
                'reboundsTotal' => $team->reboundsTotal,
                'assists' => $team->assists,
                'steals' => $team->steals,
                'blocks' => $team->blocks,
                'turnovers' => $team->turnovers,
                'foulsPersonal' => $team->foulsPersonal,
                'points' => $team->points,
                'plusMinusPoints' => $team->plusMinusPoints,
                'host' => $team->host
            ];
        });
    }
}
