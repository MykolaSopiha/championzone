@extends('layouts.app')


<!-- begin header -->
@section('page-name') Пользователи @endsection
@include('layouts.headers.home')
<!-- end header -->


@section('content')
    <!-- begin main -->
    <main class="main" role="main">
        <!-- begin items -->
        <div class="items">

            <form class="form" method="POST" action="{{url('/home/cards')}}/{{$card->id}}">

                {{ csrf_field() }}

                <input type="hidden" name="_method" value="put" />

                <header class="form__header">
                    <h2>Карта ID: {{$card->id}}</h2>
                </header>

                <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                    <label for="name">Название</label>
                    <input id="name" type="text" name="name" value="{{ $card->name }}" placeholder="Чемпионская">
                    @if ($errors->has('name'))
                        <p>{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('type') ? ' form__item--error' : '' }}">
                    <label>Тип</label>
                    <div class="card_type">
                        @foreach ($card_types as $key => $val)
                            <input type="radio" name="type" value="{{$key}}" {{ ($key == $card->type) ? 'checked' : '' }} required>{{$val}}<br>
                        @endforeach
                    </div>
                    @if ($errors->has('type'))
                        <p>{{ $errors->first('type') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('code_hash') ? ' form__item--error' : '' }}">
                    <label for="code">Номер карты</label>
                    <input id="code" type="text" name="code" value="{{ decrypt($card->code) }}" placeholder="16 digits only" required>
                    @if ($errors->has('code_hash'))
                        <p>{{ $errors->first('code_hash') }}</p>
                    @endif
                </div>

                @if (Auth::user()->status == 'admin' || Auth::user()->status == 'accountant')
                <div class="form__item{{ $errors->has('code_hash') ? ' form__item--error' : '' }}">
                    <label for="wallet">Номер кошелька</label>
                    <input id="wallet" type="text" name="wallet" value="{{ $card->wallet }}" placeholder="16 digits only">
                    @if ($errors->has('wallet'))
                        <p>{{ $errors->first('wallet') }}</p>
                    @endif
                </div>
                @endif

                <div class="form__item{{ $errors->has('cw2') ? ' form__item--error' : '' }}">
                    <label for="cw2">CW2</label>
                    <input id="cw2" type="text" name="cw2" value="{{ decrypt($card->cw2) }}" placeholder="xxx" maxlength="3" required>
                    @if ($errors->has('cw2'))
                        <p>{{ $errors->first('cw2') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('currency') ? ' form__item--error' : '' }}">
                    <label for="currency">Валюта</label>
                    <select name="currency" id="currency">
                        @foreach ($currencies as $curr)
                            <option value="{{$curr}}" {{ ($card->currency == $curr) ? 'selected' : '' }}>{{$curr}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('currency'))
                        <p>{{ $errors->first('currency') }}</p>
                    @endif
                </div>

                <div id="card_user" class="form__item{{ $errors->has('user') ? ' form__item--error' : '' }}">
                    <label for="user">Пользователь</label>
                    <select name="user_id" id="user">
                        <option value=""></option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ ($card->user_id == $user->id) ? 'selected' : ''}}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('date'))
                        <p>{{ $errors->first('user') }}</p>
                    @endif
                </div>

                <div class="form__item">
                    <button type="submit">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                    </button>
                </div>

            </form>

            <div class="form__item" align="center">
                <a href="{{url('home/cards')}}">Назад</a>
            </div>


        </div>
        <!-- end items -->
    </main>
    <!-- end main -->

@endsection