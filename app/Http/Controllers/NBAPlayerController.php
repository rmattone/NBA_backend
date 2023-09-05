<?php

namespace App\Http\Controllers;

use App\Helpers\NBAPlayersHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\NBAPlayerDetailsRequest;

class NBAPlayerController extends Controller
{
    protected $nbaPlayersHelper;

    public function __construct(NBAPlayersHelper $nbaPlayersHelper) {
        $this->nbaPlayersHelper = $nbaPlayersHelper;
    }

    public function index(NBAPlayerDetailsRequest $request)
    {
        try {
            $teams = $this->nbaPlayersHelper->getPlayer($request->validated());
            return $this->success($teams);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
