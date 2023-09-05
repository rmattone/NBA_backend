<?php

namespace App\Http\Controllers;

use App\Helpers\NBATeamsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\NBATeamsInfosRequest;
use App\Http\Requests\NBATeamsListRequest;
use App\Http\Requests\NBATeamsPlayersListRequest;

class NBATeamsController extends Controller
{
    protected $nbaTeamsHelper;

    public function __construct(NBATeamsHelper $nbaTeamsHelper)
    {
        $this->nbaTeamsHelper = $nbaTeamsHelper;
    }

    public function index(NBATeamsListRequest $request)
    {
        try {
            $teams = $this->nbaTeamsHelper->getTeams($request->validated());
            return $this->success($teams);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function infos(NBATeamsInfosRequest $request)
    {
        try {
            $teams = $this->nbaTeamsHelper->listAllInfos($request->validated());
            return $this->success($teams);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function players(NBATeamsPlayersListRequest $request)
    {
        try {
            $teams = $this->nbaTeamsHelper->getTeamPlayers($request->validated());
            return $this->success($teams);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
