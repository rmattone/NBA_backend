<?php

namespace App\Http\Controllers;

use App\Helpers\NBACustomStatisticsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\NBACustomFirstBucketsRequest;

class NBACustomStatisticsController extends Controller
{
    protected $customStatisticsHelper;

    public function __construct(NBACustomStatisticsHelper $customStatisticsHelper)
    {
        $this->customStatisticsHelper = $customStatisticsHelper;
    }

    public function firstBuckets(NBACustomFirstBucketsRequest $request)
    {
        try {
            $teams = $this->customStatisticsHelper->firstBuckets($request->validated());
            return $this->success($teams);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
