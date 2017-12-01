@extends('layouts.app')


@section('content')

    <!-- begin header -->
    @section('authQuest') У Вас нет аккаунта? @endsection
    @section('authLink') {{url('register')}} @endsection
    @section('authBtn') Регистрация @endsection
    @include('layouts.headers.auth')
    <!-- end header -->

    <!-- begin main -->
    <main class="main" role="main">

        <form class="form" method="POST" action="{{ url('/password/reset') }}">

            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <header class="form__header">
                <h1>Восстановить пароль</h1>
            </header>

            <div class="form__item{{ $errors->has('email') ? ' form__item--error' : '' }}">
                <label for="email">E-Mail</label>
                <input id="email" type="email" name="email" value="{{ $email or old('email') }}">
                @if ($errors->has('email'))
                    <p>{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="form__item{{ $errors->has('password') ? ' form__item--error' : '' }}">
                <label for="password">Новый пароль</label>
                <input id="password" type="password" name="password">
                @if ($errors->has('password'))
                    <p>{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div class="form__item{{ $errors->has('password_confirmation') ? ' form__item--error' : '' }}">
                <label for="password-confirm">Подтвердите новый пароль</label>
                <input id="password-confirm" type="password" name="password_confirmation">
                @if ($errors->has('password_confirmation'))
                    <p>{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="form__item">
                <button type="submit">
                    <i class="fa fa-btn fa-refresh"></i> Восстановить пароль
                </button>
            </div>
        </form>

    </main>
    <!-- end main -->

@endsection
