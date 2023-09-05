<?php

namespace App\Helpers;

use App\DataView\NBAPlayersDataView;
use App\Services\NBAPlayersService;

class NBAPlayersHelper
{
    protected $nbaPlayersService;
    protected $nbaPlayersDataView;

    public function __construct(
        NBAPlayersService $nbaPlayersService,
        NBAPlayersDataView $nbaPlayersDataView
    ) {
        $this->nbaPlayersService = $nbaPlayersService;
        $this->nbaPlayersDataView = $nbaPlayersDataView;
    }

    public function getPlayer(array $params)
    {
        $player = $this->nbaPlayersService->getPlayer($params);
        return $this->nbaPlayersDataView->getPlayer($player);
    }
}
