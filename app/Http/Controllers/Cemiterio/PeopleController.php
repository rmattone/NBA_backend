<?php

namespace App\Http\Controllers\Cemiterio;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\People;
use App\Services\Cemiterio\ListPeopleService;
use App\Services\Cemiterio\PeopleService;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    protected $peopleService;

    public function __construct(PeopleService $peopleService) {
        $this->peopleService = $peopleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $people = $this->peopleService->listPeople();
            return $this->success($people);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePersonRequest $request)
    {
        try {
            $store = $this->peopleService->store($request->validated());
            return $this->success($store);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $personId)
    {
        try {
            $people = $this->peopleService->show($personId);
            return $this->success($people);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePersonRequest $request, $id)
    {
        try {
            $update = $this->peopleService->update($request->validated());
            return $this->success($update);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $personId)
    {
        try {
            $delete = $this->peopleService->destroy($personId);
            return $this->success(true);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
