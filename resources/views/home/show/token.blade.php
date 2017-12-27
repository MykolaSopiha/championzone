@extends('layouts.app')

@section('content')

    <!-- begin header -->
    @section('page-name') Токены @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">


            <form class="form" method="POST" action="{{url('/home/tokens')}}/{{$token->id}}">

                {{ csrf_field() }}
                
                <input type="hidden" name="_method" value="put" />
                
                <header class="form__header">
                    <h2>Токен ID: {{$token->id}}</h2>
                </header>

                <div class="form__item">
                    <label>Дата: {{ $token->date }}</label>
                </div>

                <div class="form__item">
                    <label for="date">Карта:  {{
                        substr($token->card_code, 0, 4).' '.
                        substr($token->card_code, 4, 4).' '.
                        substr($token->card_code, 8, 4).' '.
                        substr($token->card_code, 12, 4)
                    }}</label>
                </div>

                <div class="form__item{{ $errors->has('value') ? ' form__item--error' : '' }}">
                    <label for="value">Количество денег</label>
                    <input id="value" class="money_input" type="number" step="0.01" name="value" value="{{ $token->value }}">
                    @if ($errors->has('value'))
                        <p>{{ $errors->first('value') }}</p>
                    @endif
                </div>

                @if (( Auth::user()->status === 'admin' || Auth::user()->status === 'accountant' ) && $token->rate != "USD")
                <div class="form__item{{ $errors->has('currency') ? ' form__item--error' : '' }}">
                    <label for="currency">Валюта</label>
                    <select name="currency" id="currency">
                        <option value="USD" @if ($token->currency === 'USD')  selected @endif >USD</option>
                        <option value="EUR" @if ($token->currency === 'EUR')  selected @endif >EUR</option>
                        <option value="RUB" @if ($token->currency === 'RUB')  selected @endif >RUB</option>
                        <option value="UAH" @if ($token->currency === 'UAH')  selected @endif >UAH</option>
                    </select>
                    @if ($errors->has('currency'))
                        <p>{{ $errors->first('currency') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('rate') ? ' form__item--error' : '' }}">
                    <label for="rate">Курс относительно USD</label>
                    <input id="rate" type="number" step="0.000001" min="0" name="rate" value="{{ $token->rate }}" required>
                    @if ($errors->has('rate'))
                        <p>{{ $errors->first('rate') }}</p>
                    @endif
                </div>
                @elseif ($token->rate != "USD")
                <div class="form__item">
                    <label for="status">Курс относительно USD: {{$token->rate}}</label>
                </div>
                @endif

                <div class="form__item{{ $errors->has('action') ? ' form__item--error' : '' }}">
                    <label for="action">Действие</label>
                    <select name="action" id="action">
                        <option value="deposit"  @if ($token->action === 'deposit')  selected @endif >Пополнить</option>
                        <option value="withdraw" @if ($token->action === 'withdraw') selected @endif >Списать</option>
                    </select>
                    @if ($errors->has('action'))
                        <p>{{ $errors->first('action') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('ask') ? ' form__item--error' : '' }}">
                    <label for="ask">Описание</label><br>
                    <textarea name="ask" id="ask" cols="80" rows="5" placeholder="краткий комментарий. не обязательно">{{$token->ask}}</textarea>
                    @if ($errors->has('value'))
                        <p>{{ $errors->first('value') }}</p>
                    @endif
                </div>

                @if ( Auth::user()->status === 'admin' || Auth::user()->status === 'accountant' )
                <div class="form__item{{ $errors->has('ans') ? ' form__item--error' : '' }}">
                    <label for="ans">Отзыв</label><br>
                    <textarea name="ans" id="ask" cols="80" rows="5" placeholder="отзыв бухгалтера. не обязательно">{{$token->ans}}</textarea>
                    @if ($errors->has('ans'))
                        <p>{{ $errors->first('ans') }}</p>
                    @endif
                </div>
                
                <div class="form__item">
                    <label for="status">Статус</label>
                    <select name="status" id="status">
                        @foreach ($statuses as $status)
                            @if ($status == $token->status)
                                <option value="{{$status}}" selected>{{ucfirst($status)}}</option>
                            @else
                                <option value="{{$status}}">{{ucfirst($status)}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @else
                <div class="form__item">
                    <label for="status">Статус: {{$token->status}}</label>
                </div>
                @endif

                <div class="form__item">
                    <button type="submit">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                    </button>
                </div>

                <div class="form__item" align="center">
                    <a href="{{url('home/tokens')}}">Назад</a>
                </div>

            </form>



        </div>
        <!-- end items -->
    </main>
    <!-- end main -->

@endsection