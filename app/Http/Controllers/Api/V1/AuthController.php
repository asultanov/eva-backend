<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends MainController
{
    /**
     * @param array $formData {
     * @type string $email User email address (required).
     * @type string $password User password (required).
     * @type string $password_confirmation Password confirmation (required).
     * }
     * @return JsonResponse
     * @throws ValidationException
     *
     */
    public function register(): JsonResponse
    {
        if (auth()->check())
            return response()->json(['message' => 'Сlient is already authorized'], 422);

        $data = request()->toArray();
        $fields = Validator::make($data, [
            'email' => ['required', 'string', 'email:rfc', 'max:255', function ($attribute, $value, $fail) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $fail($attribute . ' is invalid.');
                }
            }, Rule::unique(User::class)],
            'password' => ['required', 'string', 'min:6'],
        ])->validate();

        $user = User::create([
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        $token = $user->createToken($fields['email'])->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }


    /**
     * @return JsonResponse
     * @see /routes/api.php
     */
    public function login(): JsonResponse
    {
        $fields = request()->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:255', function ($attribute, $value, $fail) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $fail($attribute . ' is invalid.');
                }
            }],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password))
            return response()->json(['message' => trans('passwords.user'), 'errors' => ['user_not_found' => trans('passwords.user')]], 422);

        $token = $user->createToken($fields['email'])->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function getCurrentUser(): JsonResponse
    {
        $user = auth()->user();
        $user->load(['diagnosis', 'therapy']);
        $token = request()->bearerToken();
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    /**
     * Обновление токена пользователя
     *
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        // Проверяем, авторизован ли пользователь
        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $user = auth()->user();

        // Удаляем текущий токен
        $user->currentAccessToken()->delete();

        // Создаем новый токен
        $newToken = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $newToken
        ], 200);
    }

    /**
     * @return JsonResponse
     * @see /routes/api.php
     */
    public function logout(): JsonResponse
    {
        if (!auth()->check())
            abort(403);
        $user = auth()->user();
        //$user->tokens->each(function ($token, $key) {
        //    $token->delete();
        //});
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 201);
    }
}
