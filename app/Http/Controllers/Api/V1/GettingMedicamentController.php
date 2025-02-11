<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GettingMedicament;
use Illuminate\Support\Facades\Auth;

class GettingMedicamentController extends Controller
{
    /**
     * Получение списка препаратов с пагинацией.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Количество записей на странице (по умолчанию 10)
        $user = Auth::user();

        $medicaments = GettingMedicament::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate($perPage);

        return response()->json($medicaments);
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

        $medicament = GettingMedicament::create([
            'user_id' => $user->id,
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
            'dose_mg' => $validatedData['dose_mg'] ?? null,
            'dose_me' => $validatedData['dose_me'] ?? null,
        ]);

        return response()->json([
            'message' => 'Препарат успешно сохранен',
            'data' => $medicament
        ], 201);
    }

    /**
     * Удаление записи.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $medicament = GettingMedicament::where('id', $id)->where('user_id', $user->id)->first();

        if (!$medicament) {
            return response()->json(['message' => 'Запись не найдена или у вас нет прав на удаление'], 404);
        }

        $medicament->delete();

        return response()->json(['message' => 'Препарат успешно удален'], 200);
    }
}
