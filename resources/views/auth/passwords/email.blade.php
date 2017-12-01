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
        <form class="form" method="POST" action="{{ url('/password/email') }}">
            
            {{ csrf_field() }}

            <header class="form__header">
                <h1>Забыли свой пароль?</h1>
                <h3>Введите ниже свой E-mail и Вам будет отправлено сообщение для восстановления пароля</h3>
            </header>

            @if (session('status'))
            <div class="form__item form__item--mail-send">
                <p>{{ session('status') }}</p>
            </div>
            @endif

            <div class="form__item{{ $errors->has('email') ? ' form__item--error' : '' }}">
                <label for="email">E-Mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="champion@gmail.com">
                @if ($errors->has('email'))
                    <p>{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="form__item">
                <button type="submit">
                    <i class="fa fa-btn fa-envelope"></i> Отправить сообщение
                </button>
            </div>

            <div class="form__pass-reset">
                <a href="{{ url('/login') }}">Страница входа</a>
            </div>

        </form>
    </main>
    <!-- end main -->

@endsection
