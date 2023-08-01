<?php

namespace App\DataView;

use App\Models\User;

class UserDataView
{

    public function getInfo(User $user)
    {
        return [
            'id' => $user->userId,
            'name' => $user->name,
            'roles' => ['admin'],
        ];
    }

    public function showUser(User $user)
    {
        $profileIds = $user->profiles->pluck('profileId');
        return [
            'userId' => $user->id,
            'email' => $user->email,
            'personId' => $user->personId,
            'status' => $user->status,
            'profiles' => $profileIds
        ];
    }
}
