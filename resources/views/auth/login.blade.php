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

        <form class="form" method="POST" action="{{ url('/login') }}">

            {{ csrf_field() }}

            <header class="form__header">
                <h1>Sign in to Champion Zone</h1>
                <h2>Enter your details below</h2>
            </header>

            <div class="form__item{{ $errors->has('email') ? ' form__item--error' : '' }}">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
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

            <div class="form__item">
                <input type="checkbox" id="remember" name="remember"> Remember Me
            </div>

            <div class="form__item">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-btn fa-sign-in"></i> Login
                </button>
            </div>

            <div class="form__pass-reset">
                <a href="{{ url('/password/reset') }}">Forgot Your Password?</a>
            </div>

        </form>

    </main>
@endsection
