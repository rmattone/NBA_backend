<?php

namespace App\Services\Cemiterio;

use App\Models\People;

class PeopleService
{
    public static function listPeople()
    {
        $query = People::query();

        return $query->get();
    }

    public static function show(int $personId)
    {
        $query = People::where('personId', '=', $personId);

        return $query->get();
    }

    public static function store(array $params)
    {
        $person = new People($params);
        return $person->save();
    }
    
    public static function update(array $params)
    {
        $person = People::where('personId', '=', $params['personId'])->first();
        unset($params['personId']);
        $person->fill($params);
        return $person->save();
    }

    public function destroy(int $personId)
    {
        $query = People::where('personId', '=', $personId);

        return $query->delete();
    }
}
