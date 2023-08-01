<?php

namespace App\Http\Controllers\Users;

use App\Helpers\UsersHelper;
use App\Http\Controllers\Controller;
use App\Services\Users\UsersService;

class UsersController extends Controller
{

    protected $usersService;
    protected $usersHelper;

    public function __construct(
        UsersService $usersService,
        UsersHelper $usersHelper
    )
    {
        $this->usersService = $usersService;
        $this->usersHelper = $usersHelper;
    }

    public function index()
    {
        try {
            $users = $this->usersService->listUsers();
            return $this->success($users);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }


    public function getUser()
    {
        try {
            $user = $this->usersHelper->getInfos(auth()->user()->id);
            return $this->success($user);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
