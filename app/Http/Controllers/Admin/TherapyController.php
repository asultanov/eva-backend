<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guides\Therapy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TherapyController extends MainController
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|string',
        ];
        return Validator::make($data, $rules);
    }

    public function index()
    {
        $this->template = 'admin.guides.therapies';
        return $this->renderOutput();
    }

    public function getTherapies()
    {
        $objects = Therapy::get();
        return response()->json($objects, 200, []);
    }

    public function editTherapy(Request $request)
    {
        if ($this->validator($request->all())->fails()) {
            return response()->json(["status" => 'error', 'message' => $this->validator($request->all())->errors()->all()]);
        }


        if (is_null($request->id))
            $object = new Therapy;
        elseif (!is_null($request->id))
            $object = Therapy::findOrFail($request->id);
        else
            return ["status" => 'error', "message" => ['У вас нет соответствующих прав для выполнения этого действия']];

        return response()->json($this->formData($object, $request));
    }

    protected function formData($object, $request)
    {
        $object->name = $request->name;

        return ($object->save())
            ? ["status" => 'success', "message" => 'Изменения успешно внесены в систему']
            : ["status" => 'error', "message" => ['Непредвиденная ошибка']];
    }

    public function removeTherapy(Request $request)
    {
        foreach ($request->ids as $id) {
            $object = Therapy::where('id', $id)->first();
            $messages[] = $object->delete()
                ? $object->name . ' успешно удален'
                : $object->name . ' не была удален';
        }
        return response()->json(['status' => 'success', 'message' => $messages]);
    }
}
