<?php

namespace App\Helpers;

use App\DataView\NBATeamsDataView;
use App\Models\NBAPlayByPlay;
use App\Models\NBATeam;
use App\Services\NBATeamsService;

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
        $games = $this->nbaTeamsService->games($team);
        $roster = $this->nbaTeamsDataView->listTeamPlayers($team);
        $teamData = array_merge(
            $this->nbaTeamsDataView->listTeam($team),
            $this->listFirstAttempt($games, $roster, $team->nbaTeamId),
            $this->listGamesStats($games->slice(0,5)),
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
                    'date' => $game->date
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
            $firstTry = $game->playByPlay->slice(0,10)->filter(function ($possesion) use ($playersName, $isHost) {
                $fistWord = !is_null($possesion->$isHost) ? explode(' ', $possesion->$isHost)[0] : null;
                return (in_array($fistWord, $playersName) || $fistWord == 'MISS') && !is_null($fistWord);
            })->first();
            try {
                $description = explode(' ', $firstTry->$isHost);
                return $description[0] == 'MISS' ? $description[1] : $description[0];
            } catch (\Throwable $th) {
                dd($game);
                return null;
            }
        });

        $firstBasket = [
            'firstAttempts' => $this->contarNomes($playByPlay->toArray())
        ];
        return $firstBasket;
    }

    function contarNomes($listaNomes) {
        $contagemNomes = [];
    
        foreach ($listaNomes as $nome) {
            if (isset($contagemNomes[$nome])) {
                $contagemNomes[$nome]++;
            } else {
                $contagemNomes[$nome] = 1;
            }
        }
    
        $resultado = [];
        foreach ($contagemNomes as $nome => $vezes) {
            $resultado[] = ["name" => $nome, "times" => $vezes];
        }
    
        // Ordenar o resultado pelo número de vezes (em ordem decrescente)
        usort($resultado, function ($a, $b) {
            return $b['times'] - $a['times'];
        });
    
        return $resultado;
    }
}
