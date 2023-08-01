<?php

namespace App\DataView;

use App\Helpers\PaginatorHelper;
use Carbon\Carbon;

class PeopleDataView
{

    
    private $paginatorHelper;

    public function __construct(
        PaginatorHelper $paginatorHelper
    ) {
        $this->paginatorHelper = $paginatorHelper;
    }


    public function listPeople($list, $params)
    {
        $items = $list->map(function($person){
            $person->birth = Carbon::parse($person->birth)->format('d/m/Y');
            $person->death = Carbon::parse($person->death)->format('d/m/Y');

            return $person;
        });
        if(array_key_exists('page', $params)){
            return $this->paginatorHelper->paginate($items, $params['perPage'] ?? 20, $params['page'] ?? 1);
        }
        return $items;
    }
}
