<?php

namespace App\Services\Cemiterio;

use App\Helpers\FileHelper;
use App\Models\People;
use Carbon\Carbon;

class PeopleService
{
    public function listPeople(array $params)
    {
        $query = People::query()
            ->orderBy('name')
            ->when(isset($params), function ($q) use ($params) {
                $q->where('name', 'like', '%'.$params['query'].'%');
            });

        return $query->get();
    }

    public function show(int $personId)
    {
        $query = People::where('personId', '=', $personId);

        return $query->get();
    }

    public function store(array $params)
    {
        $person = new People($params);
        $data = [
            "name" => $params['name'],
            'birth' => isset($params['birth']) ? $this->prepareDateMerge($params['birth']) : null,
            'death' => isset($params['death']) ? $this->prepareDateMerge($params['death']) : null,
            "description" => $params['description'],
            "birthPlace" => $params['birthPlace'],
            "address" => $params['address'],
        ];
        if (isset($params['photo'])) {
            $data['photo'] = FileHelper::fileToBase64($params['photo']);
        }
        $person->fill($data);
        
        return $person->save();
    }

    public function update(array $params)
    {
        $data = [
            "name" => $params['name'],
            'birth' => isset($params['birth']) ? $this->prepareDateMerge($params['birth']) : null,
            'death' => isset($params['death']) ? $this->prepareDateMerge($params['death']) : null,
            "description" => $params['description'],
            "birthPlace" => $params['birthPlace'],
            "address" => $params['address'],
        ];
        if (isset($params['photo'])) {
            $data['photo'] = FileHelper::fileToBase64($params['photo']);
        }
        $person = People::where('personId', '=', $params['personId'])->firstOrFail();

        $person->fill($data);
        return $person->save();
    }

    private function prepareDateMerge($date)
    {
        return Carbon::createFromFormat('d/m/Y',  $date)->format('Y-m-d');
    }
}
