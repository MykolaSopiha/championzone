@extends('layouts.app')

@section('content')

    <!-- begin header -->
    @section('page-name') Карты @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">


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
                        @foreach ($card_types as $ct)
                            @if ($ct[0] == $card->type) 
                                <input type="radio" name="type" value="{{$ct[0]}}" checked required>{{$ct[1]}}<br>
                            @else
                                <input type="radio" name="type" value="{{$ct[0]}}" required>{{$ct[1]}}<br>
                            @endif
                        @endforeach
                    </div>
                    @if ($errors->has('type'))
                        <p>{{ $errors->first('type') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('code_hash') ? ' form__item--error' : '' }}">
                    <label for="code">Номер карты</label>
                    <input id="code" type="text" name="code" value="{{ $card->code }}" placeholder="16 digits only" required>
                    @if ($errors->has('code_hash'))
                        <p>{{ $errors->first('code_hash') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('cw2') ? ' form__item--error' : '' }}">
                    <label for="cw2">CW2</label>
                    <input id="cw2" type="text" name="cw2" value="{{ $card->cw2 }}" placeholder="xxx" maxlength="3" required>
                    @if ($errors->has('cw2'))
                        <p>{{ $errors->first('cw2') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('date') ? ' form__item--error' : '' }}">
                    <label for="date">Дата</label>
                    <input id="date" class="card_date" type="text" name="date" value="{{ substr($card->date, 0, -3) }}" placeholder="Введите дату" required>
                    @if ($errors->has('date'))
                        <p>{{ $errors->first('date') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('currency') ? ' form__item--error' : '' }}">
                    <label for="currency">Валюта</label>
                    <select name="currency" id="currency">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="RUB">RUB</option>
                        <option value="UAH">UAH</option>
                    </select>
                    @if ($errors->has('currency'))
                        <p>{{ $errors->first('currency') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('user') ? ' form__item--error' : '' }}">
                    <label for="user">Пользователь</label>
                    <select name="user" id="user">
                        @foreach ($users as $user)
                            @if ($card->user_id == $user->id)
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
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

                <div class="form__item" align="center">
                    <a href="{{url('home/cards')}}">Назад</a>
                </div>

            </form>



        </div>
        <!-- end items -->
    </main>
    <!-- end main -->

@endsection