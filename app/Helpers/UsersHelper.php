<?php

namespace App\Helpers;

use App\Models\User;
use App\Services\Users\UsersService;
use App\DataView\UserDataView;

class UsersHelper
{
    protected $usersService;
    protected $usersDataView;

    public function __construct(
        UsersService $usersService,
        UserDataView $usersDataView
    ) {
        $this->usersService = $usersService;
        $this->usersDataView = $usersDataView;
    }

    public function getInfos(int $userId)
    {

        $user = $this->usersService->show($userId);
        $user = $this->usersDataView->getInfo($user);
        return $user;
    }

    public function showUser(int $userId)
    {
        $user = $this->usersService->show($userId);
        return $this->usersDataView->showUser($user);
    }
}
