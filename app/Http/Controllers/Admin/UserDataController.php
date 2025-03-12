<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserDataController extends MainController
{
    public function index($id)
    {
        $this->template = 'admin.user-data.user-data';
        $user = User::with('diagnosis', 'therapy','bloodTest','bloodCount','gettingTherapies.therapy')->find($id);

        $this->vars = [
            'user' => $user,
        ];
        return $this->renderOutput();
    }
}
