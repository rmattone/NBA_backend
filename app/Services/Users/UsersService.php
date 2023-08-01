<?php

namespace App\Services\Users;

use App\Models\User;

class UsersService
{
    public static function listUsers()
    {
        $query = User::select([
            'id',
            'email',
            'personId',
            'status'
        ]);

        return $query->get();
    }

    public static function store(array $params)
    {
        $user = new User([
            'email' => $params['email'],
            'password' => bcrypt($params['password'] ?? 'clubeemedezoito!'),
            'personId' => $params['personId'],
            'status' => $params['status'],
        ]);
        $user->save();
        if (array_key_exists('profiles', $params)) {
            $user->profiles()->sync($params['profiles']);
        }
        return $user;
    }

    public static function update(array $params)
    {
        $user = User::find($params['id']);
        $user->fill($params);

        if (array_key_exists('profiles', $params)) {
            $user->profiles()->sync($params['profiles']);
        }
        return $user->save();
    }

    public function show(int $userId)
    {
        $user = User::where('id', '=', $userId)
            ->get()
            ->first();

        return $user;
    }
}
