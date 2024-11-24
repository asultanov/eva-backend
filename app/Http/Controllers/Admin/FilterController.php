<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserFilter;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    protected $routes = [
        'users' => 'users',
    ];


    protected $allowed_params = [
        'grant', 'members', 'task', 'users'
    ];

    public function saveFilter(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        $data = $request->except(['_token', 'type']);

        if (in_array($request->type, $this->allowed_params)) {
            $filter = UserFilter::where([['user_id', auth()->user()->id], ['type', $request->type]])->first();
            if ($filter)
                $filter->update(['data' => $data]);
            else
                UserFilter::create(['type' => $request->type, 'user_id' => auth()->id(), 'data' => $data]);
        }
        return ['status' => 'success', 'type' => 'save_filter']; //redirect()->route($this->routes[$request->type ?? 'home']);
    }

    public function clearFilter()
    {
        $type = request('type', '');
        if (in_array($type, $this->allowed_params)) {
            UserFilter::where([['user_id', auth()->id()], ['type', $type]])->delete();
        }
        return ['status' => 'success', 'type' => 'clear_filter']; //redirect()->route($this->routes[$type ?? 'home']);
    }
}
