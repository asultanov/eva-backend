<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserBloodChemistryTest;
use Illuminate\Support\Facades\Auth;

class UserBloodChemistryTestController extends Controller
{
    /**
     * Получение списка анализов с пагинацией.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Количество записей на странице (по умолчанию 10)
        $user = Auth::user();

        $bloodChemistryTests = UserBloodChemistryTest::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate($perPage);

        return response()->json($bloodChemistryTests);
    }

    /**
     * Создание новой записи.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'nullable|date',
            'total_protein' => 'required|numeric',
            'total_bilirubin' => 'required|numeric',
            'direct_bilirubin' => 'required|numeric',
            'indirect_bilirubin' => 'required|numeric',
            'urea' => 'required|numeric',
            'creatinine' => 'required|numeric',
            'alt' => 'required|numeric',
            'ast' => 'required|numeric',
            'glucose' => 'required|numeric',
            'cholesterol' => 'required|numeric',
            'uric_acid' => 'required|numeric',
        ]);

        $user = Auth::user();

        $bloodChemistryTest = UserBloodChemistryTest::create([
            'user_id' => $user->id,
            'date' => $validatedData['date'] ?? now(),
            'total_protein' => $validatedData['total_protein'],
            'total_bilirubin' => $validatedData['total_bilirubin'],
            'direct_bilirubin' => $validatedData['direct_bilirubin'],
            'indirect_bilirubin' => $validatedData['indirect_bilirubin'],
            'urea' => $validatedData['urea'],
            'creatinine' => $validatedData['creatinine'],
            'alt' => $validatedData['alt'],
            'ast' => $validatedData['ast'],
            'glucose' => $validatedData['glucose'],
            'cholesterol' => $validatedData['cholesterol'],
            'uric_acid' => $validatedData['uric_acid'],
        ]);

        return response()->json([
            'message' => 'Анализ успешно сохранен',
            'data' => $bloodChemistryTest
        ], 201);
    }

    /**
     * Удаление записи.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $bloodChemistryTest = UserBloodChemistryTest::where('id', $id)->where('user_id', $user->id)->first();

        if (!$bloodChemistryTest) {
            return response()->json(['message' => 'Запись не найдена или у вас нет прав на удаление'], 404);
        }

        $bloodChemistryTest->delete();

        return response()->json(['message' => 'Анализ успешно удален'], 200);
    }
}
