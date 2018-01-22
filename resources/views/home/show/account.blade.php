@extends('layouts.app')


@section('styles')
    <style>
        .input-group-addon {
            cursor: pointer;
        }
    </style>
@endsection

<!-- begin header -->
@section('page-name') {{ $data->name }} @endsection
@include('layouts.headers.home')
<!-- end header -->


@section('content')
    <!-- begin main -->
    <main class="main" role="main">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">

                    <form method="POST" action="{{url('/home/users/')}}/{{$data->id}}">
                        <input type="hidden" name="user_id" value="{{$data->id}}">

                        {{ csrf_field() }}

                        <header class="">
                            <h2>Личные данные ID: {{ $data->id }}</h2>
                            {{--<div class="row">--}}
                                {{--<div class="col-sm-6">--}}
                                    {{--<div class="input-group">--}}
                                        {{--<input type="text" id="copyTarget" class="form-control" aria-describedby="refLinkHelp" readonly value="{{$ref_link}}">--}}
                                        {{--<div id="copyButton" class="input-group-addon" data-container="body" data-toggle="popover" data-hide-popover="true" data-placement="top" data-content="Текст скопирован!">--}}
                                            {{--<i class="fa fa-files-o"></i>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<small id="refLinkHelp" class="form-text text-muted">Ваша реферальная ссылка.</small>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-6">--}}
                                    {{--@if (empty($patron))--}}
                                        {{--<div class="form-group form__item{{ $errors->has('ref_id') ? ' form__item--error' : '' }}">--}}
                                            {{--<input type="text" name="ref_link" class="form-control" aria-describedby="refHelp">--}}
                                            {{--<small id="refHelp" class="form-text text-muted">Введите реферальную ссылку Вашего тимлида</small>--}}
                                            {{--@if ($errors->has('ref_id'))--}}
                                                {{--<p>{{ $errors->first('ref_id') }}</p>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--@else--}}
                                        {{--<div class="form-group form__item{{ $errors->has('ref_id') ? ' form__item--error' : '' }}">--}}
                                            {{--<div class="input-group">--}}
                                                {{--<input type="text" readonly class="form-control" aria-describedby="teamLeadHelp" value="{{$patron}}">--}}
                                                {{--<div class="input-group-addon">--}}
                                                    {{--<a href="{{url('home/users/1/edit?removeref=true')}}">--}}
                                                        {{--<i class="fa fa-times" aria-hidden="true"></i>--}}
                                                    {{--</a>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<small id="teamLeadHelp" class="form-text text-muted">Ваш тимлид</small>--}}
                                            {{--@if ($errors->has('ref_id'))--}}
                                                {{--<p>{{ $errors->first('ref_id') }}</p>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </header>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                                    <label for="name">Nickname:</label>
                                    <input id="name" class="form-control" type="text" name="name" value="{{$data->name}}">
                                    @if ($errors->has('name'))
                                        <p>{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group form__item{{ $errors->has('first_name') ? ' form__item--error' : '' }}">
                                    <label for="first_name">Имя</label>
                                    <input id="first_name" class="form-control" type="text" name="first_name" value="{{$data->first_name}}">
                                    @if ($errors->has('first_name'))
                                        <p>{{ $errors->first('first_name') }}</p>
                                    @endif
                                </div>
                                <div class="form-group form__item{{ $errors->has('last_name') ? ' form__item--error' : '' }}">
                                    <label for="last_name">Фамилия</label>
                                    <input id="last_name" class="form-control" type="text" name="last_name" value="{{$data->last_name}}">
                                    @if ($errors->has('last_name'))
                                        <p>{{ $errors->first('last_name') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form__item{{ $errors->has('birthday') ? ' form__item--error' : '' }}">
                                    <label for="birthday">Дата рождения</label>
                                    <input id="birthday" class="form-control pick_birthday" type="text" name="birthday" value="{{$data->birthday}}" placeholder="Введите дату">
                                    @if ($errors->has('birthday'))
                                        <p>{{ $errors->first('birthday') }}</p>
                                    @endif
                                </div>
                                <div class="form-group form__item{{ $errors->has('terra_id') ? ' form__item--error' : '' }}">
                                    <label for="terra_id">Terra Leads ID</label>
                                    <input id="terra_id" class="form-control" type="text" name="terra_id" value="{{$data->terra_id}}">
                                    @if ($errors->has('terra_id'))
                                        <p>{{ $errors->first('terra_id') }}</p>
                                    @endif
                                </div>
                                @if ( Auth::user()->status === 'admin' )
                                    <div class="form__item form-group">
                                        <label for="status">Account Status</label>
                                        <select name="status" id="status" class="form-control">
                                            @foreach ($roles as $role)
                                                <option value="{{$role}}" {{($role == $data->role) ? 'selected' : '' }}>{{ucfirst($role)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="form__item form-group">
                                        <label for="status">Account Status:</label>
                                        <input type="text" class="form-control" readonly value="{{$data->status}}">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group text-center">
                            {{--<div class="checkbox">--}}
                            {{--<label><input type="checkbox" name="email-notification" value="">Уведомления на E-mail</label>--}}
                            {{--</div>--}}
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                            </button>
                        </div>
                    </form>

                    @if (!$refs->isEmpty())
                    <h2>Моя комманда</h2>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Никнейм</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($refs as $ref)
                            <tr>
                                <td>{{$ref->id}}</td>
                                <td>{{$ref->first_name}}</td>
                                <td>{{$ref->last_name}}</td>
                                <td>{{$ref->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif

                </div>
            </div>
        </div>
    </main>
    <!-- end main -->
@endsection