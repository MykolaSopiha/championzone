@extends('layouts.app')

@section('content')

    <!-- begin header -->
    @section('page-name') {{ Auth::user()->name }} @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <form class="form" method="POST" action="{{url('/home/account/')}}/{{$data->id}}">

                {{ csrf_field() }}

                <header class="form__header">
                    <h2>Личные данные</h2>
                </header>

                <div class="form__item">
                    <label>User ID: {{ $data->id }}</label>
                    <input type="hidden" name="user_id" value="{{$data->id}}">
                </div>

                <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                    <label for="name">Nickname:</label>
                    <input id="name" type="text" name="name" value="{{ $data->name }}">
                    @if ($errors->has('name'))
                        <p>{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('first_name') ? ' form__item--error' : '' }}">
                    <label for="first_name">Имя</label>
                    <input id="first_name" type="text" name="first_name" value="{{ $data->first_name }}">
                    @if ($errors->has('first_name'))
                        <p>{{ $errors->first('first_name') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('last_name') ? ' form__item--error' : '' }}">
                    <label for="last_name">Фамилия</label>
                    <input id="last_name" type="text" name="last_name" value="{{ $data->last_name }}">
                    @if ($errors->has('last_name'))
                        <p>{{ $errors->first('last_name') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('birthday') ? ' form__item--error' : '' }}">
                    <label for="birthday">Дата рождения</label>
                    <input id="birthday" class="pick_birthday" type="text" name="birthday" value="{{ $data->birthday }}" placeholder="Введите дату">
                    @if ($errors->has('date'))
                        <p>{{ $errors->first('date') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('terra_id') ? ' form__item--error' : '' }}">
                    <label for="terra_id">Terra Leads ID</label>
                    <input id="terra_id" type="text" name="terra_id" value="{{ $data->terra_id }}">
                    @if ($errors->has('terra_id'))
                        <p>{{ $errors->first('terra_id') }}</p>
                    @endif
                </div>

                @if ( Auth::user()->status === 'admin' )
                <div class="form__item">
                    <label for="status">Account Status</label>
                    <select name="status" id="status">
                        @foreach ($statuses as $status)
                            @if ($status == $data->status)
                                <option value="{{$status}}" selected>{{ucfirst($status)}}</option>
                            @else
                                <option value="{{$status}}">{{ucfirst($status)}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @else
                <div class="form__item">
                    <label for="status">Account Status: {{Auth::user()->status}}</label>
                </div>
                @endif

                <div class="form__item">
                    <button type="submit">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                    </button>
                </div>

            </form>

        </div>
    </main>
    <!-- end main -->

@endsection