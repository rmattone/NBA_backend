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
            $this->listGamesStats($games, $team->nbaTeamId),
            $roster
        );
        return $teamData;
    }

    public function listGamesStats($games, $teamId)
    {
        $listGames = [
            'games' => [],
            'statistics' => []
        ];
        $points = [];
        $plusMinusPoints = [];
        $blocks = [];
        $steals = [];
        $reboundsTotal = [];
        $turnovers = [];
        foreach ($games as $game) {
            $gameStats = $this->nbaTeamsDataView->teamStats($game->teamStats);
            $team = $gameStats->filter(function ($teamStat) use ($teamId) {
                return $teamStat['teamId'] == $teamId;
            })->first();

            $points[] = $team['points'];
            $plusMinusPoints[] = $team['plusMinusPoints'];
            $blocks[] = $team['blocks'];
            $steals[] = $team['steals'];
            $reboundsTotal[] = $team['reboundsTotal'];
            $turnovers[] = $team['turnovers'];
            $dates[] = Carbon::parse($game->date)->format('d/m');


            $listGames['games'][] = [
                'info' => [
                    'gameId' => $game->gameId,
                    'date' => Carbon::parse($game->date)->format('d/m/Y')
                ],
                'teamStats' => $gameStats
            ];
        }

        $listGames['statistics'] = [
            'dates' => array_reverse($dates),
            'Points' => [
                'data' => array_reverse($points),
                'min' => min($points),
                'max' => max($points),
                'avg' => round(array_sum($points) / count($points), 1),
            ],
            'Plus/Minus Points' => [
                'min' => min($plusMinusPoints),
                'max' => max($plusMinusPoints),
                // 'avg' => round(array_sum($plusMinusPoints)/count($plusMinusPoints), 1),
                'avg' => round($this->standDeviation($plusMinusPoints), 1),
            ],
            'Blocks' => [
                'min' => min($blocks),
                'max' => max($blocks),
                'avg' => round(array_sum($blocks) / count($blocks), 1),
            ],
            'Steals' => [
                'min' => min($steals),
                'max' => max($steals),
                'avg' => round(array_sum($steals) / count($steals), 1),
            ],
            'Rebounds' => [
                'min' => min($reboundsTotal),
                'max' => max($reboundsTotal),
                'avg' => round(array_sum($reboundsTotal) / count($reboundsTotal), 1),
            ],
            'Turnovers' => [
                'min' => min($turnovers),
                'max' => max($turnovers),
                'avg' => round(array_sum($turnovers) / count($turnovers), 1),
            ],
        ];

        return $listGames;
    }

    function standDeviation($arr)
    {
        $num_of_elements = count($arr);

        $variance = 0.0;

        // calculating mean using array_sum() method
        $average = array_sum($arr) / $num_of_elements;

        foreach ($arr as $i) {
            // sum of squares of differences between 
            // all numbers and means.
            $variance += pow(($i - $average), 2);
        }

        return (float)sqrt($variance / $num_of_elements);
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
