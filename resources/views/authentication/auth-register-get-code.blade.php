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
      //'resources/assets/js/phone-input.js'
    ])

    <script type="module">

        $(document).on('click', '#get-code', function () {
            const input_group = $(`.input-group`);
            if ($('#email').val() == '') {
                $('.invalid-feedback').remove();
                input_group.addClass('is-invalid');
                input_group.after(`<div class="invalid-feedback">Поле email обязательно к заполнению</div>`);
                return false;
            }
            $.ajax({
                url: "{{ route('guest.getCode') }}",
                method: "POST",
                data: {email: $('#email').val()},
                success: () => {
                    $('.invalid-feedback').remove();
                    $('.feedback').remove();
                    input_group.after(`<div class="feedback"><p>Дождитесь кода регистрации, введите его в поле ниже и нажмите кнопку "Продолжить"</p></div>`);
                },
                error: (response) => {
                    $('.invalid-feedback').remove();
                    $('.feedback').remove();
                    if (response.status === 422) {
                        const errors = response.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            input_group.addClass('is-invalid');
                            input_group.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                        });
                    }
                }
            });
        });
    </script>
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
                    <h3 class="mb-1">Регистрация</h3>
                    <p class="mb-4">Сделайте управление вашим приложением простым и увлекательным!</p>

                    <form class="mb-3" method="POST" action="{{ route('guest.checkCode') }}">
                        <label class="form-label" for="email">E-mail</label>
                        <div class="input-group mb-1">
                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" type="email" name="email" placeholder="E-mail"
                                   aria-describedby="email" value="{{ old('email') ?? request('email')}}" tabindex="4" autofocus/>
                            <button class="btn btn-outline-primary waves-effect" type="button" id="get-code">Получить код</button>
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="mb-1">
                            <input type="text" class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ request('code') }}" name="code" placeholder="Код регистрации" required>
                            @if ($errors->has('code'))
                                <div class="error">{{ $errors->first('code') }}</div>
                            @endif
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
