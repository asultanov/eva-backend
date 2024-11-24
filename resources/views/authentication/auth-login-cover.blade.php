@php
    $configData = PageConfig::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Вход в систему')

@section('vendor-style')

@endsection

@section('page-style')
    @vite([
      'resources/assets/vendor/scss/pages/page-auth.scss'
    ])
@endsection

@section('vendor-script')

@endsection

@section('page-script')
    @vite([
      //'resources/assets/js/phone-input.js'
    ])
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/illustrations/auth-login-illustration-'.$configData['style'].'.png') }}" alt="auth-login-cover" class="img-fluid my-5 auth-illustration"
                         data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.png">

                    <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.png') }}" alt="auth-login-cover" class="platform-bg"
                         data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="{{url('/')}}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros',["height"=>20,"withbg"=>'fill: #fff;'])</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h3 class=" mb-1">Welcome to {{config('variables.templateName')}}! 👋</h3>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    <form class="mb-3" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label" for="email">E-mail</label>
                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email"
                                   type="email" placeholder="e-mail" name="email" aria-describedby="email" value="{{ old('email') }}"
                                   tabindex="4" autofocus/>
                            @if ($errors->has('email'))
                                <span class="error">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="mb-2 form-password-toggle">
                            <label class="form-label" for="password">Пароль</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password"/>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                            @if ($errors->has('password'))
                                <span class="error">{{ $errors->first('password') }}</span>
                            @endif
                        </div>


                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me">
                                <label class="form-check-label" for="remember-me">Запомнить меня</label>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100">Войти в систему</button>
                    </form>
                    @if (Route::has('register'))
                        <p class="text-center">
                            <span>Еще не зарегистрированы?</span>
                            <a href="{{route('register')}}">
                                <span>Зарегистрируйтесь</span>
                            </a>
                        </p>
                    @endif
                    {{--
                    <div class="divider my-4">
                        <div class="divider-text">or</div>
                    </div>
                        <div class="d-flex justify-content-center">
                            <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
                                <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
                                <i class="tf-icons fa-brands fa-google fs-5"></i>
                            </a>

                            <a href="javascript:;" class="btn btn-icon btn-label-twitter">
                                <i class="tf-icons fa-brands fa-twitter fs-5"></i>
                            </a>
                        </div>
                    --}}
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
@endsection
