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
                'tg_id' => $item->tg_id,
                'tg_login' => $item->tg_login,
                'tg_name' => $item->tg_name,
                'phone' => $item->phone,
                'discount' => $item->discount,
                'expire_date' => $item->expire_date ? $item->expire_date->format('Y-m-d H:i') : null,
                'roles' => $item->roles,
            ];
        });
        return $users;
    }
}
