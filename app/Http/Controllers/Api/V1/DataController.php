<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Guides\Diagnosis;
use App\Models\Guides\Medicament;
use App\Models\Guides\Therapy;
use Illuminate\Http\Request;

class DataController extends Controller
{

    public function getDiagnosesAndTherapies()
    {
        $diagnosis = Diagnosis::get();
        $therapies = Therapy::get();

        return response()->json([
            'diagnosis' => $diagnosis,
            'therapies' => $therapies,
        ], 200, []);
    }

    public function getDiagnoses()
    {
        $objects = Diagnosis::get();
        return response()->json($objects, 200, []);
    }

    public function getTherapies()
    {
        $objects = Therapy::get();
        return response()->json($objects, 200, []);
    }

    public function getMedicaments()
    {
        $objects = Medicament::get();
        return response()->json($objects, 200, []);
    }
}
