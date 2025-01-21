<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends MainController
{
    public function update()
    {

        request()->validate([
            'diagnosis_id' => 'nullable|integer',
            'therapy_id' => 'nullable|integer',
            'birthday' => ['nullable', 'date', 'date_format:Y-m-d', 'before:today', 'after:1900-01-01'],
        ]);


        $user = auth()->user();
        $user->name = request('name');
        $user->last_name = request('last_name');
        $user->patronymic = request('patronymic');
        $user->birthday = request('birthday');
        $user->phone = phoneFormatter(request('phone'));
        $user->full_name = request('name') . ' ' . request('last_name') . ' ' . request('patronymic');
        $user->diagnosis_id = request('diagnosis_id');
        $user->therapy_id = request('therapy_id');
        $user->save();
        $user->fresh();
        $user->load(['diagnosis', 'therapy']);
        $token = request()->bearerToken();
        return response()->json(['user' => $user, 'token' => $token], 201);
    }
}
