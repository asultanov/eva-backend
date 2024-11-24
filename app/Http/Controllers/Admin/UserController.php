<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\UserFilter;
use App\Tranformers\UsersTransformer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends MainController
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
//            if (\Gate::denies('ACCESS_USERS')) {
//                return abort('403');
//            }
            return $next($request);
        });
    }

    protected function validator(array $data, $uid = null)
    {
        $rules = [
            'tg_login' => ['nullable', 'string', 'max:255'],
            'tg_id' => ['nullable', 'string', 'max:255'],
            'tg_name' => ['nullable', 'string', 'max:255'],
            'expire_date' => ['nullable', 'date'],
            'userrole' => 'nullable|array',
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];

        if (isset($data['password'])) {
            $rules['phone'] = 'required|string|max:18|unique:users,phone';
            $rules['password'] = 'required|string|min:4|confirmed';
        } else {
            $rules['phone'] = 'required|string|max:18|unique:users,phone,' . $uid;
        }
        return Validator::make($data, $rules);
    }

    public function users()
    {
        $filter = UserFilter::where([['user_id', auth()->id()], ['type', 'users']])->first();

        $this->template = 'admin.users.users';
        $this->vars = [
            'roles' => Role::all(),
            'data' => ($filter) ? $filter->data : null,
        ];
        return $this->renderOutput();
    }

    public function allUsers(UsersTransformer $transformer)
    {
        $filter = UserFilter::where([['user_id', auth()->user()->id], ['type', 'users']])->first();
        $data = ($filter) ? $filter->data : null;

        $users = User::query();
        $users->with(['roles:id,name_ru as text']);

        $users->when(isset($data['name']), function ($q) use ($data) {
            return $q->where('name', 'LIKE', "%{$data['name']}%");
        })->when(isset($data['last_name']), function ($q) use ($data) {
            return $q->where('last_name', 'LIKE', "%{$data['last_name']}%");
        })->when(isset($data['patronymic']), function ($q) use ($data) {
            return $q->where('patronymic', 'LIKE', "%{$data['patronymic']}%");
        })->when(isset($data['from_date']), function ($q) use ($data) {
            return $q->where('birthday', '>=', $data['from_date']);
        })->when(isset($data['to_date']), function ($q) use ($data) {
            return $q->where('birthday', '<=', $data['to_date']);
        })->when(isset($data['role']), function ($q) use ($data) {
            return $q->whereHas('roles', function ($query) use ($data) {
                $query->where('role_id', $data['role']);
            });
        });


        $responce = [
            'total' => $users->count(),
            'totalNotFiltered' => User::count(),
            'rows' => $transformer->usersTransform($users->when(request()->has('limit'), function ($q) {
                return $q->skip(request('offset', 0))->take(request('limit', 10));
            })->get())
        ];
        return response()->json($responce);
    }

    public function fastEdit(Request $request)
    {
        $data = $request->except('_token');
        $data['phone'] = phoneFormatter($data['phone']);
        if ($this->validator($data, $request->id)->fails()) {
            return response()->json(["status" => 'error', 'message' => $this->validator($data)->errors()->all()]);
        }

        if (is_null($request->id)) {
            $user = new User;
            $user->password = Hash::make(null);
        } else {
            try {
                if (auth()->user()->hasRole('root') && $request->id == auth()->user()->id && !in_array(1, $request->userrole)) {
                    return ['status' => 'error', 'message' => 'Нельзя лишить себя статуса супер пользователя'];
                }
            } catch (\TypeError $e) {
                return ['status' => 'error', 'message' => 'Ну раз уж мы получили эту ошибку значит не всё потеряно и у тебя есть возможность остаться супер пользователем'];
            }
            $user = User::find($request->id);
        }

        $user->tg_login = $request->tg_login;
        $user->tg_id = $request->tg_id;
        $user->tg_name = $request->tg_name;
        $user->phone = $request->phone;
        $user->expire_date = $request->expire_date;
        $user->discount = $request->discount ?? 0;


        try {
            $user->save();
            $user->roles()->sync($request->userrole);
            return ["status" => 'success', "message" => 'Пользователь успешно создан'];
        } catch (QueryException $e) {
            return ["status" => 'error', "message" => $e->getMessage()];
        }
    }

    public function deleteUser(Request $request)
    {
        if (auth()->user()->id == $request->id) {
            return ['status' => 'error', 'message' => 'Нельзя удалить себя'];
        }
        $user = User::find($request->id);

        try {
            $user->roles()->detach();
            $user->delete();
            return ["status" => 'success', "message" => 'Пользователь успешно удален'];
        } catch (QueryException $e) {
            return ["status" => 'error', "message" => $e->getMessage()];
        }
    }

    public function specAuth(User $user)
    {
        if (auth()->user()->id == $user->id) {
            return back()->with(['status' => 'danger', 'message' => 'Вы уже авторизованы']);
        }
        session(['beforeUser' => auth()->user()->id]);
        auth()->loginUsingId($user->id);
        if (!auth()->user()->hasRole('root')) {
            return redirect()->route('home');
        }
        return redirect()->intended();
    }

}
