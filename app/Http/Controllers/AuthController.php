<?php

namespace App\Http\Controllers;


use App\Jobs\SendActivationCode;
use App\Models\Code;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function getCode(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ]
        ]);

        $object = Code::where('email', $request->email)->first();

        if ($object) {
            $elapsedTime = $object->created_at->diffInSeconds(now());
            $remainingTime = max(0, 120 - $elapsedTime);
            $remainingTime = ceil($remainingTime); // Округляем до целого вверх

            if ($remainingTime > 0) {
                $secondsWord = $this->getSecondsWord($remainingTime);

                return response()->json([
                    'message' => "",
                    'errors' => [
                        "code" => [
                            "Код уже был отправлен. Новый код можно получить через {$remainingTime} {$secondsWord}"
                        ]
                    ]
                ], 422);
            }

            $object->delete();
        }

        $code = $this->generateCode();

        Code::create([
            'email' => $request->email,
            'code' => $code
        ]);

        SendActivationCode::dispatch($request->email, $code);
        return response()->json(['message' => "Код успешно создан"]);
    }

    public function sendCode($email, $code)
    {
        $client = new Client([
            'base_uri' => env('SELENIUM_BASE_URI'),
            'timeout' => 200
        ]);

        $response = $client->post('/send-message', [
            'json' => [
                'user-data-dir' => env('SELENIUM'),
                'email' => $email,
                'text' => "Ваш код для регистрации а сайте: $code",
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
        return true;
    }

    private function getSecondsWord($number)
    {
        $number = abs($number) % 100;
        $lastDigit = $number % 10;

        if ($number > 10 && $number < 20) {
            return 'секунд';
        }
        if ($lastDigit > 1 && $lastDigit < 5) {
            return 'секунды';
        }
        if ($lastDigit == 1) {
            return 'секунда';
        }

        return 'секунд';
    }


    public function checkCode(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'code' => ['required', 'string'],
        ]);

        if (Code::where([['email', $request->email], ['code', $request->code]])->exists()) {
            session()->put('email', $request->email);
            Code::where([['email', $request->email], ['code', $request->code]])->delete();
            return back();
        }

        return back()->withErrors(['code' => 'Код неверен. Пожалуйста, проверьте введенный код.'])->withInput();
    }

    protected function generateCode(): string
    {
        $code = strtolower(\Str::random(6));
        if (Code::whereCode($code)->count()) {
            $code = $this->generateCode();
        }
        return $code;
    }
}
