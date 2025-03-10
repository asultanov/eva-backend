<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GettingTherapy;
use Illuminate\Support\Facades\Auth;

class GettingTherapyController extends Controller
{
    /**
     * Получение списка препаратов с пагинацией.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Количество записей на странице (по умолчанию 10)
        $user = Auth::user();

        $therapies = GettingTherapy::where('user_id', $user->id)
            ->with('therapy') // Загружаем данные о препарате
            ->orderBy('date', 'desc')
            ->paginate($perPage);

        return response()->json($therapies);
    }

    /**
     * Создание новой записи.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'dose_mg' => 'nullable|numeric',
            'dose_me' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        if (!$user->therapy_id) {
            return response()->json([
                'message' => 'Не установлен вид принимаемой терапии',
                'status' => 'error'
            ], 401);
        }

        $therapy = GettingTherapy::create([
            'user_id' => $user->id,
            'therapy_id' => $user->therapy_id,
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
            'dose_mg' => $validatedData['dose_mg'] ?? null,
            'dose_me' => $validatedData['dose_me'] ?? null,
        ]);

        return response()->json([
            'message' => 'Запись о приеме терапии успешно добавлена',
            'data' => $therapy->load('therapy') // Загружаем связанные данные
        ], 201);
    }

    /**
     * Удаление записи.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $therapy = GettingTherapy::where('id', $id)->where('user_id', $user->id)->first();

        if (!$therapy) {
            return response()->json(['message' => 'Запись не найдена или у вас нет прав на удаление'], 404);
        }

        $therapy->delete();

        return response()->json(['message' => 'Запись о приеме терапии успешно удалена'], 200);
    }
}
