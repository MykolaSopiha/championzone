@extends('layouts.app')


@section('styles')
    <style>
        .input-group-addon {
            cursor: pointer;
        }
    </style>
@endsection


@section('content')

    <!-- begin header -->
    @section('page-name') {{ $user->name }} @endsection
    @include('layouts.headers.home')
    <!-- end header -->


    <!-- begin main -->
    <main class="main" role="main">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">

                    <form method="POST" action="{{url('/home/users/')}}/{{$user->id}}">
                        <input type="hidden" name="user_id" value="{{$user->id}}">

                        {{ csrf_field() }}

                        <header class="">
                            <h2 style="text-align: center;">Личные данные: ID {{ $user->id }}</h2>
                        </header>

                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">Nickname:</label>
                                    <input id="name" class="form-control" type="text" name="name" value="{{$user->name}}">
                                    @if ($errors->has('name'))
                                        <p>{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="first_name">Имя</label>
                                    <input id="first_name" class="form-control" type="text" name="first_name" value="{{$user->first_name}}" required>
                                    @if ($errors->has('first_name'))
                                        <p>{{ $errors->first('first_name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <label for="last_name">Фамилия</label>
                                    <input id="last_name" class="form-control" type="text" name="last_name" value="{{$user->last_name}}" required>
                                    @if ($errors->has('last_name'))
                                        <p>{{ $errors->first('last_name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                    <label for="birthday">Дата рождения</label>
                                    <input id="birthday" class="form-control pick_birthday" type="text" name="birthday" value="{{$user->birthday}}" placeholder="Введите дату" required>
                                    @if ($errors->has('birthday'))
                                        <p>{{ $errors->first('birthday') }}</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="status">Account Status:</label>
                                    @if (Auth::user()->status === 'admin')
                                        <select name="status" id="status" class="form-control">
                                            @foreach ($roles as $role)
                                                <option value="{{$role}}" {{($role == $user->status) ? 'selected' : '' }}>{{ucfirst($role)}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control" value="{{$user->status}}" readonly>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="team">Команда:</label>
                                    @if ($user->TeamLead())
                                        <span>{{Auth::user()->team->name}} - Team Lead</span>
                                    @else
                                        <select name="team_id" id="team" class="form-control">
                                            <option value="">Выберите команду</option>
                                            @foreach ($teams as $team)
                                                <option value="{{$team->id}}" {{($team->id == $user->team_id) ? 'selected' : '' }}>{{$team->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('terra_id'))
                                            <p>{{ $errors->first('terra_id') }}</p>
                                        @endif
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
    <!-- end main -->
@endsection