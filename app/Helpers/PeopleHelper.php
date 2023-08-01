<?php

namespace App\Helpers;

use App\DataView\PeopleDataView;
use App\Services\Cemiterio\PeopleService;

class PeopleHelper
{

    private $peopleService;
    private $peopleDataView;

    public function __construct(
        PeopleService $peopleService,
        PeopleDataView $peopleDataView
    ) {
        $this->peopleService = $peopleService;
        $this->peopleDataView = $peopleDataView;
    }

    public function listPeople(array $params)
    {
        $people = $this->peopleService->listPeople($params);
        $items = $this->peopleDataView->listPeople($people, $params);

        return $items;

    }
}
