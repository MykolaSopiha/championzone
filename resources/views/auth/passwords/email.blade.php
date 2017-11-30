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
        <form class="form" method="POST" action="{{ url('/password/email') }}">
            
            {{ csrf_field() }}

            <header class="form__header">
                <h1>Forgot your password?</h1>
                <h3>Enter your email address below and we'll get you back on track</h3>
            </header>

            @if (session('status'))
            <div class="form__item form__item--mail-send">
                <p>{{ session('status') }}</p>
            </div>
            @endif

            <div class="form__item{{ $errors->has('email') ? ' form__item--error' : '' }}">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="champion@gmail.com">
                @if ($errors->has('email'))
                    <p>{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="form__item">
                <button type="submit">
                    <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                </button>
            </div>

            <div class="form__pass-reset">
                <a href="{{ url('/login') }}">Back to Sign In</a>
            </div>

        </form>
    </main>
    <!-- end main -->

@endsection
