<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest;

class CustomLoginRequest extends LoginRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login' => ['required_if:phone,null', 'string'],
            'phone' => ['required_if:login,null', 'string'],
            'password' => 'required|string',
        ];
    }
}
