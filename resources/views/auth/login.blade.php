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

        <form class="form" method="POST" action="{{ url('/login') }}">

            {{ csrf_field() }}

            <header class="form__header">
                <h1>Вход в Зону Чемпиона</h1>
                <h2>введите ваши данные</h2>
            </header>

            <div class="form__item{{ $errors->has('email') ? ' form__item--error' : '' }}">
                <label for="email">E-Mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <p>{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="form__item{{ $errors->has('password') ? ' form__item--error' : '' }}">
                <label for="password">Пароль</label>
                <input id="password" type="password" name="password">
                @if ($errors->has('password'))
                    <p>{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div class="form__item">
                <input type="checkbox" id="remember" name="remember"> Запомнить меня
            </div>

            <div class="form__item">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-btn fa-sign-in"></i> Войти
                </button>
            </div>

            <div class="form__pass-reset">
                <!-- <a href="{{ url('/password/reset') }}">Забыли пароль?</a> -->
            </div>

        </form>

    </main>
@endsection
