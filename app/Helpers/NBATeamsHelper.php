<?php

namespace App\Helpers;

use App\DataView\NBATeamsDataView;
use App\Models\NBAPlayByPlay;
use App\Models\NBATeam;
use App\Services\NBATeamsService;
use Carbon\Carbon;

class NBATeamsHelper
{
    protected $nbaTeamsService;
    protected $nbaTeamsDataView;

    public function __construct(
        NBATeamsService $nbaTeamsService,
        NBATeamsDataView $nbaTeamsDataView
    ) {
        $this->nbaTeamsService = $nbaTeamsService;
        $this->nbaTeamsDataView = $nbaTeamsDataView;
    }

    public function getTeams(array $params)
    {
        $teams = $this->nbaTeamsService->listTeams($params);
        return $teams->map(function ($team) {
            return $this->nbaTeamsDataView->listTeam($team);
        });
    }

    public function getTeamPlayers(array $params)
    {
        $teams = $this->nbaTeamsService->listTeams($params);
        return $teams->map(function ($team) {
            return  array_merge(
                $this->nbaTeamsDataView->listTeam($team),
                $this->nbaTeamsDataView->listTeamPlayers($team),
            );
        });
    }

    public function listAllInfos(array $params)
    {
        $team = NBATeam::find($params['teamId']);
        $games = $this->nbaTeamsService->games($team, $params);
        $roster = $this->nbaTeamsDataView->listTeamPlayers($team);
        $teamData = array_merge(
            $this->nbaTeamsDataView->listTeam($team),
            $this->listFirstAttempt($games, $roster, $team->nbaTeamId),
            $this->listGamesStats($games),
            $roster
        );
        return $teamData;
    }

    public function listGamesStats($games)
    {
        $listGames = ['games' => []];
        foreach ($games as $game) {
            $gameStats = $this->nbaTeamsDataView->teamStats($game->teamStats);
            $listGames['games'][] = [
                'info' => [
                    'gameId' => $game->gameId,
                    'date' => Carbon::parse($game->date)->format('d/m/Y')
                ],
                'teamStats' => $gameStats
            ];
        }
        return $listGames;
    }

    public function listFirstAttempt($games, $roster, $nbaTeamId)
    {
        $playersName = $roster['players']->map(function ($player) {
            return $player['familyName'];
        })->toArray();
        $playByPlay = $games->map(function ($game) use ($playersName, $nbaTeamId) {
            $isHost = $game->teamStats->filter(function ($team) use ($nbaTeamId) {
                return $team->teamId == $nbaTeamId;
            })->first()->host ? 'homeDescription' : 'visitorDescription';
            $firstTry = $game->playByPlay->slice(0, 10)->filter(function ($possesion) use ($playersName, $isHost) {
                $fistWord = !is_null($possesion->$isHost) ? explode(' ', $possesion->$isHost)[0] : null;
                return (in_array($fistWord, $playersName) || $fistWord == 'MISS') && !is_null($fistWord);
            })->first();
            try {
                $description = explode(' ', $firstTry->$isHost);
                return $description[0] == 'MISS' ? [$description[1], false] : [$description[0], true];
            } catch (\Throwable $th) {
                return null;
            }
        });

        $firstBasket = [
            'firstAttempts' => $this->countNames($playByPlay->toArray())
        ];
        return $firstBasket;
    }

    function countNames($nameList)
    {
        $nameCounts = [];

        foreach ($nameList as [$name, $fgMade]) {
            if ($name !== null && $name !== "") {
                if (isset($nameCounts[$name])) {
                    $nameCounts[$name]['fgAttempted']++;
                    if ($fgMade) {
                        $nameCounts[$name]['fgMade']++;
                    }
                } else {
                    $nameCounts[$name] = ['fgAttempted' => 1, 'fgMade' => $fgMade ? 1 : 0];
                }
            }
        }

        $result = [];
        foreach ($nameCounts as $name => $data) {
            $result[] = [
                "name" => $name,
                "fgMade" => $data['fgMade'],
                "fgAttempted" => $data['fgAttempted']
            ];
        }

        // Sort the result by 'fgMade' in descending order
        usort($result, function ($a, $b) {
            return $b['fgMade'] - $a['fgMade'];
        });

        return $result;
    }
    
}
