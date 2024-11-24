<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\CustomLoginRequest;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

//        Fortify::authenticateUsing(function (Request $request) {
//            $phone = phoneFormatter($request->phone);
//            $user = \App\Models\User::where('phone', $phone)->first();
//
//            if ($user &&
//                Hash::check($request->password, $user->password)) {
//                return $user;
//            }
//        });

        Fortify::loginView(function () {
            session()->forget('email');
            return view('authentication.auth-login-cover', ['pageConfigs' => ['myLayout' => 'blank', 'hasCustomizer' => false]]);
        });

        Fortify::registerView(function () {
            if (session()->has('email')) {
                //session()->forget('phone');
                return view('authentication.auth-register-cover', ['pageConfigs' => ['myLayout' => 'blank', 'hasCustomizer' => false]]);
            } else {
                return view('authentication.auth-register-get-code', ['pageConfigs' => ['myLayout' => 'blank', 'hasCustomizer' => false]]);
            }
        });


        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('telegram-login', function (Request $request) {
            $throttleKey = Str::lower($request->ip());
            return Limit::perMinute(4)->by($throttleKey);
        });

        $this->app->singleton(LoginRequest::class, CustomLoginRequest::class);
    }
}
