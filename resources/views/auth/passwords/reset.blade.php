@extends('layouts.app')


@section('content')

    <!-- begin header -->
    @section('authQuest') Don't have an account? @endsection
    @section('authLink') {{url('register')}} @endsection
    @section('authBtn') sign up @endsection
    @include('layouts.headers.auth')
    <!-- end header -->

    <!-- begin main -->
    <main class="main" role="main">

        <form class="form" method="POST" action="{{ url('/password/reset') }}">

            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <header class="form__header">
                <h1>Reset Password</h1>
            </header>

            <div class="form__item{{ $errors->has('email') ? ' form__item--error' : '' }}">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email" name="email" value="{{ $email or old('email') }}">
                @if ($errors->has('email'))
                    <p>{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="form__item{{ $errors->has('password') ? ' form__item--error' : '' }}">
                <label for="password">Password</label>
                <input id="password" type="password" name="password">
                @if ($errors->has('password'))
                    <p>{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div class="form__item{{ $errors->has('password_confirmation') ? ' form__item--error' : '' }}">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation">
                @if ($errors->has('password_confirmation'))
                    <p>{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="form__item">
                <button type="submit">
                    <i class="fa fa-btn fa-refresh"></i> Reset Password
                </button>
            </div>
        </form>

    </main>
    <!-- end main -->

@endsection
