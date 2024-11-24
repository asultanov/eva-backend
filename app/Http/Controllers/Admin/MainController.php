<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use App\Menu\AdminMenu;

class MainController extends BaseController
{
    protected $template;
    protected $vars = [];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            //if (!auth()->user()->is_admin) {
            //    abort(403);
            //}
            return $next($request);
        });
    }

    protected function renderOutput()
    {
        $this->vars['menuData'] = (new AdminMenu(true))->menuCombine();
        return view($this->template, $this->vars);
    }
}
