<?php

namespace App\Tranformers;

use App\Models\Grants\GrantTask;
use Illuminate\Support\Collection;

class UsersTransformer
{
    public function usersTransform(Collection $users)
    {
        $users->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'last_name' => $item->last_name,
                'patronymic' => $item->patronymic,
                'birthday' => $item->birthday,
                'phone' => $item->phone,
                'email' => $item->email,
                'roles' => $item->roles,
                'diagnosis' => $item->diagnosis,
                'therapy' => $item->therapy,
            ];
        });
        return $users;
    }
}
