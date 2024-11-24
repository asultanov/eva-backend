<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends MainController
{
    public function index()
    {
        $this->template = 'admin.home.home';
        return $this->renderOutput();
    }
}
