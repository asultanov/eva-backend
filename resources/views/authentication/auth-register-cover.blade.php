@php
    $configData = PageConfig::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Регистрация')

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
      'resources/assets/js/phone-input.js'
    ])
@endsection

@section('content')
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">

            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/illustrations/auth-register-illustration-'.$configData['style'].'.png') }}" alt="auth-register-cover" class="img-fluid my-5 auth-illustration"
                         data-app-light-img="illustrations/auth-register-illustration-light.png" data-app-dark-img="illustrations/auth-register-illustration-dark.png">

                    <img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.png') }}" alt="auth-register-cover" class="platform-bg"
                         data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png">
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Register -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="{{url('/')}}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros',["height"=>20,"withbg"=>'fill: #fff;'])</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h3 class="mb-1">Adventure starts here 🚀</h3>
                    <p class="mb-4">Make your app management easy and fun!</p>

                    <form class="mb-3" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label" for="last-name">Фамилия</label>
                            <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last-name" type="text" maxlength="18" name="last_name" placeholder="Фамилия"
                                   aria-describedby="last_name" value="{{ old('last_name') }}" tabindex="1" autofocus/>
                            @if ($errors->has('last_name'))
                                <span class="error">{{ $errors->first('last_name') }}</span>
                            @endif
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="name">Имя</label>
                            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" type="text" maxlength="18" name="name" placeholder="Имя"
                                   aria-describedby="name" value="{{ old('name') }}" tabindex="2" autofocus/>
                            @if ($errors->has('name'))
                                <span class="error">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="mb-1">
                            <label class="form-label" for="patronymic">Отчество</label>
                            <input class="form-control{{ $errors->has('patronymic') ? ' is-invalid' : '' }}" id="patronymic" type="text" maxlength="18" name="patronymic"
                                   placeholder="Отчество" aria-describedby="patronymic" value="{{ old('patronymic') }}" tabindex="3" autofocus/>
                            @if ($errors->has('patronymic'))
                                <span class="error">{{ $errors->first('patronymic') }}</span>
                            @endif
                        </div>
                        {{--
                            <div class="mb-1">
                                <label class="form-label" for="phone">Телефон</label>
                                <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" type="tel" data-tel-input maxlength="18" name="phone" placeholder="Телефон"
                                       aria-describedby="phone" value="{{ phoneFormatter(session('phone'), true) }}" tabindex="4" autofocus/>
                                @if ($errors->has('phone'))
                                    <span class="error">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                        --}}
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

                        <div class="mb-2 form-password-toggle">
                            <label class="form-label" for="password-confirmation">Повторите пароль</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password-confirmation" class="form-control"
                                       name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password_confirmation"/>
                                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
                                <label class="form-check-label" for="terms-conditions">
                                    I agree to
                                    <a href="javascript:void(0);">privacy policy & terms</a>
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100">Присоединиться</button>
                    </form>

                    <p class="text-center mt-2">
                        <span>У вас уже есть учетная запись?</span>
                        <a href="{{ route('login') }}">
                            <span>Вместо этого войдите в систему</span>
                        </a>
                    </p>

                    <div class="divider my-4">
                        <div class="divider-text">Или</div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="https://{{ config('tgbot.link') }}"> <span>&nbsp;Сбросить пароль</span></a>
                    </div>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
@endsection
