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
                    <label for="value">Количество денег ({{ $token->currency }})</label>
                    <input id="value" class="money_input" type="number" step="0.01" name="value" value="{{ $token->value }}">
                    @if ($errors->has('value'))
                        <p>{{ $errors->first('value') }}</p>
                    @endif
                </div>

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
                    <textarea name="ask" id="ask" cols="80" rows="5" placeholder="краткий комментарий. не обязательно"></textarea>
                    @if ($errors->has('value'))
                        <p>{{ $errors->first('value') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('ans') ? ' form__item--error' : '' }}">
                    <label for="ans">Отзыв</label><br>
                    <textarea name="ans" id="ask" cols="80" rows="5" placeholder="отзыв бухгалтера. не обязательно"></textarea>
                    @if ($errors->has('ans'))
                        <p>{{ $errors->first('ans') }}</p>
                    @endif
                </div>

                @if ( Auth::user()->status === 'admin' || Auth::user()->status === 'accountant' )
                <div class="form__item">
                    <label for="status">Token Status</label>
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
                    <label for="status">Account Status: {{Auth::user()->status}}</label>
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