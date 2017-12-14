@extends('layouts.app')


@section('content')

    <!-- begin header -->
    @section('authQuest') У Вас уже есть аккаунт? @endsection
    @section('authLink') {{url('login')}} @endsection
    @section('authBtn') Войти @endsection
    @include('layouts.headers.auth')
    <!-- end header -->

    <!-- begin main -->
    <main class="main" role="main">
        <form class="form" method="POST" action="{{ url('/register') }}">
            
            {{ csrf_field() }}

            <header class="form__header">
                <h1>Давайте начнем</h1>
                <h2>Делай профит. Делай объем!</h2>
            </header>


            <div class="form__item {{ $errors->has('name') ? 'form__item--error' : '' }}">
                <label for="name">Логин</label>
                <input id="name" type="text" name="name" maxlength="255" value="{{ old('name') }}" placeholder="Champion" required>
                @if ($errors->has('name'))
                    <p>{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div class="form__item {{ $errors->has('email') ? 'form__item--error' : '' }}">
                <label for="email">E-mail</label>
                <input id="email" type="text" name="email" value="{{ old('email') }}" placeholder="champion@example.com" required>
                @if ($errors->has('email'))
                    <p>{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="form__item {{ $errors->has('password') ? 'form__item--error' : '' }}">
                <label for="password">Пароль</label>
                <input id="password" type="password" name="password" minlength="4" value="{{ old('password') }}" placeholder="6+ символов" required>
                @if ($errors->has('email'))
                    <p>{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div class="form__item {{ $errors->has('password_confirmation') ? 'form__item--error' : '' }}">
                <label for="password_confirmation">Подтвердите пароль</label>
                <input id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required>
                @if ($errors->has('password_confirmation'))
                    <p>{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

<!--             <div class="form__item {{ $errors->has('terra_id') ? 'form__item--error' : '' }}">
                <label for="terra_id">TerraLeads ID</label>
                <input id="terra_id" type="text" name="terra_id" value="{{ old('terra_id') }}" placeholder="Enter your terra leads id" required>
                @if ($errors->has('terra_id'))
                    <p>{{ $errors->first('terra_id') }}</p>
                @endif
            </div> -->

            <div class="submit">
                <button type="submit"><i class="fa fa-btn fa-user"></i> Регистрация</button>
            </div>

        </form>
    </main>
    <!-- end main -->
@endsection
