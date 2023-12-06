<?php

namespace App\Helpers;

use App\DataView\NBATeamsDataView;
use App\Models\NBATeam;
use App\Services\NBATeamsService;

class NBACustomStatisticsHelper
{
    protected $nbaTeamsService;
    protected $nbaTeamsDataView;
    protected $nbaTeamsHelper;

    public function __construct(
        NBATeamsService $nbaTeamsService,
        NBATeamsDataView $nbaTeamsDataView,
        NbaTeamsHelper $nbaTeamsHelper
    ) {
        $this->nbaTeamsService = $nbaTeamsService;
        $this->nbaTeamsDataView = $nbaTeamsDataView;
        $this->nbaTeamsHelper = $nbaTeamsHelper;
    }

    public function firstBuckets(array $params)
    {
        $teamsId = explode(',', $params['teamsId']);
        $teamsStats = [];
        foreach ($teamsId as $id) {
            $team = NBATeam::find($id);
            $games = $this->nbaTeamsService->games($team, $params);
            $roster = $this->nbaTeamsDataView->listTeamPlayers($team);

            $teamsStats[] = array_merge(
                [
                    'team' => $this->nbaTeamsDataView->listTeam($team),
                ],
                $this->nbaTeamsHelper->listFirstAttempt($games, $roster, $team->nbaTeamId)
            );
        }
        return $teamsStats;
    }
}
