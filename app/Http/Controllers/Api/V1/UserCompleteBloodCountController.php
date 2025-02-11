<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCompleteBloodCount;
use Illuminate\Support\Facades\Auth;

class UserCompleteBloodCountController extends Controller
{
    /**
     * Получение списка анализов с пагинацией.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Количество записей на странице (по умолчанию 10)
        $user = Auth::user();

        $bloodTests = UserCompleteBloodCount::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate($perPage);

        return response()->json($bloodTests);
    }

    /**
     * Создание новой записи.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'nullable|date|date_format:Y-m-d',
            'wbc' => 'required|numeric',
            'rbc' => 'required|numeric',
            'plt' => 'required|numeric',
            'hb' => 'required|numeric',
            'ht' => 'required|numeric',
            'stab_neutrophils' => 'required|numeric',
            'seg_neutrophils' => 'required|numeric',
            'lymphocytes' => 'required|numeric',
            'monocytes' => 'required|numeric',
            'eosinophils' => 'required|numeric',
            'basophils' => 'required|numeric',
            'esr' => 'required|numeric',
        ]);

        $user = Auth::user();

        $bloodTest = UserCompleteBloodCount::create([
            'user_id' => $user->id,
            'date' => $validatedData['date'] ?? now(),
            'wbc' => $validatedData['wbc'],
            'rbc' => $validatedData['rbc'],
            'plt' => $validatedData['plt'],
            'hb' => $validatedData['hb'],
            'ht' => $validatedData['ht'],
            'stab_neutrophils' => $validatedData['stab_neutrophils'],
            'seg_neutrophils' => $validatedData['seg_neutrophils'],
            'lymphocytes' => $validatedData['lymphocytes'],
            'monocytes' => $validatedData['monocytes'],
            'eosinophils' => $validatedData['eosinophils'],
            'basophils' => $validatedData['basophils'],
            'esr' => $validatedData['esr'],
        ]);

        return response()->json([
            'message' => 'Анализ успешно сохранен',
            'data' => $bloodTest
        ], 201);
    }

    /**
     * Удаление записи.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $bloodTest = UserCompleteBloodCount::where('id', $id)->where('user_id', $user->id)->first();

        if (!$bloodTest) {
            return response()->json(['message' => 'Запись не найдена или у вас нет прав на удаление'], 404);
        }

        $bloodTest->delete();

        return response()->json(['message' => 'Анализ успешно удален'], 200);
    }
}
