<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guides\Medicament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicamentController extends MainController
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    protected function validator(array $data): \Illuminate\Validation\Validator
    {
        $rules = [
            'name' => 'required|string',
        ];
        return Validator::make($data, $rules);
    }

    public function index()
    {
        $this->template = 'admin.guides.medicaments';
        return $this->renderOutput();
    }

    public function getMedicaments()
    {
        $objects = Medicament::get();
        return response()->json($objects, 200, []);
    }

    public function editMedicament(Request $request)
    {
        if ($this->validator($request->all())->fails()) {
            return response()->json(["status" => 'error', 'message' => $this->validator($request->all())->errors()->all()]);
        }


        if (is_null($request->id))
            $object = new Medicament;
        elseif (!is_null($request->id))
            $object = Medicament::findOrFail($request->id);
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

    public function removeMedicament(Request $request)
    {
        foreach ($request->ids as $id) {
            $object = Medicament::where('id', $id)->first();
            $messages[] = $object->delete()
                ? $object->name . ' успешно удален'
                : $object->name . ' не была удален';
        }
        return response()->json(['status' => 'success', 'message' => $messages]);
    }
}
