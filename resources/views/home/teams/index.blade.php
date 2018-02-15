@extends('layouts.app')

@section('styles')
    <style>
        .ui-datepicker-calendar, .ui-datepicker-current {
            display: none !important;
        }

        #all_cards tfoot {
            display: table-header-group;
        }

        .chosen-single {
            height: 70px !important;
            line-height: 70px !important;
            background: none !important;
            min-width: 150px;
            font-size: 18px;
            text-align: center;
            border-radius: 10px !important;
        }

        .chosen-container {
            min-width: 150px;
        }

        .filter .chosen-single {
            height: 34px !important;
            line-height: 34px !important;
            background: none !important;
            min-width: 150px;
            font-size: 14px;
            text-align: left;
            border-radius: 4px !important;
        }
    </style>
@endsection


@section('content')
<!-- begin header -->
@section('page-name') Команды @endsection
@include('layouts.headers.home')
<!-- end header -->

<!-- begin main -->
<main class="main" role="main">
    <div class="main-inner">

        <!-- begin items -->
        <div class="items">

            <!-- begin items__add -->
            <div class="items__add">
                <form class="form" method="POST" action="{{route('home.teams.store')}}">

                    {!! csrf_field() !!}

                    <header class="form__header">
                        <h2>Добавить команду</h2>
                    </header>

                    <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                        <label for="name">Название команды</label>
                        <input id="name" type="text" name="name" value="{{old('name')}}" required>
                        @if ($errors->has('name'))
                            <p>{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="form__item{{ $errors->has('team_lead_id') ? ' form__item--error' : '' }}">
                        <label for="team_lead">Лидер команды</label>
                        <select name="team_lead_id" id="team_lead" class="chosen-js-select">
                            @foreach ($users as $user)
                                @if ($user->first_name == "" || $user->last_name == "")
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @else
                                    <option value="{{ $user->id }}">{{ $user->first_name." ".$user->last_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @if ($errors->has('team_lead_id'))
                            <p>{{ $errors->first('team_lead_id') }}</p>
                        @endif
                    </div>

                    <div class="form__item">
                        <button type="submit">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Добавить
                        </button>
                    </div>

                </form>
            </div>
            <!-- end item__add -->


            <!-- begin items__list -->
            <div class="items__list">
                <h2>Список команд</h2>
                <form class="js-form" action="#" method="post">
                    <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="table-responsive">
                        <table class="table js-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Лидер</th>
                                <th>Учасники</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teams as $team)
                                <tr>
                                    <td>{{$team->id}}</td>
                                    <td>{{$team->name}}</td>
                                    <td>{{$team->leader->first_name." ".$team->leader->last_name." ".$team->leader->name}}</td>
                                    <td>{{count($team->user)}}</td>
                                    <td style="text-align: right;">
                                        <a href="{{route('home.teams.show', $team->id)}}" class="btn btn-primary">Учасники</a>
                                        <a href="{{route('home.teams.destroy', $team->id)}}"
                                           class="remove-btn btn btn-danger">Удалить</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <!-- end items__list -->

        </div>
        <!-- end items -->

    </div>
</main>
<!-- end main -->

@endsection