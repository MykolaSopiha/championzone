@extends('layouts.app')


<!-- begin content -->
@section('content')

    <!-- begin header -->
    @section('page-name') Токены @endsection
    @include('layouts.headers.home')
    <!-- end header -->

    <!-- begin main -->
    <main role="main">
        <div class="container">
               
            <form class="form-horizontal" method="POST" action="{{url('/home/tokens')}}/{{$token->id}}">


                <header class="form__header text-center" style="margin-top: 25px; margin-bottom: 25px;">
                    <h2>Токен ID: {{$token->id}}</h2>
                    <p>Дата: {{ $token->date }}</p>
                </header>


                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put" />


                <div class="row" style="padding-bottom: 20px;">

                    <div class="col-sm-5 col-sm-offset-1 col-xs-8 col-xs-offset-2" style="padding-top: 27px;">
                    
                        <div class="form-group form__item{{ $errors->has('value') ? ' form__item--error' : '' }}">
                            <label for="value" class="col-sm-4 control-label">Деньги</label>
                            <div class="col-sm-8">
                                <input id="value" class="form-control money_input" type="text" step="0.01" name="value" value="{{ $token->value }}">
                                <p>* В валюте карты</p>
                            </div>
                            @if ($errors->has('value'))
                                <p>{{ $errors->first('value') }}</p>
                            @endif
                        </div>

                        @if (Auth::user()->status == 'admin' || Auth::user()->status == 'accountant')
                            <div class="form-group form__item{{ $errors->has('date') ? ' form__item--error' : '' }}">
                                <label for="date" class="col-sm-4 control-label">Дата</label>
                                <div class="col-sm-8">
                                    <input id="date" class="form-control pick_date" type="text" name="date" placeholder="Введите дату" value="{{ $token->date }}" required>
                                </div>
                                @if ($errors->has('date'))
                                    <p>{{ $errors->first('date') }}</p>
                                @endif
                            </div>

                            <div class="form-group form__item{{ $errors->has('currency') ? ' form__item--error' : '' }}">
                                <label for="currency" class="col-sm-4 control-label">Валюта</label>
                                <div class="col-sm-8">
                                    <select name="currency" id="currency" class="form-control">
                                        <option value="USD" @if ($token->currency === 'USD')  selected @endif >USD</option>
                                        <option value="EUR" @if ($token->currency === 'EUR')  selected @endif >EUR</option>
                                        <option value="RUB" @if ($token->currency === 'RUB')  selected @endif >RUB</option>
                                        <option value="UAH" @if ($token->currency === 'UAH')  selected @endif >UAH</option>
                                    </select>
                                </div>
                                @if ($errors->has('currency'))
                                    <p>{{ $errors->first('currency') }}</p>
                                @endif
                            </div>
                        @endif

                        @if (Auth::user()->status == 'admin' || Auth::user()->status == 'accountant')
                            <div class="form-group form__item{{ $errors->has('rate') ? ' form__item--error' : '' }}">
                                <label for="rate" class="col-sm-4 control-label">Курс {{$token->currency}}/USD</label>
                                <div class="col-sm-8">
                                    <input id="rate" type="number" class="form-control" step="0.000001" min="0" name="rate" value="{{ $token->rate }}" required><br>
                                    @if ($errors->has('rate'))
                                        <p>{{ $errors->first('rate') }}</p>
                                    @endif
                                    <button type="button" id="get_rate" class="btn btn-primary">
                                        <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Обновить курс
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="form__item">
                                <label for="status">Курс {{$token->currency}} относительно USD на {{$token->date}}: {{$token->rate}}</label>
                            </div>
                        @endif

                        <div class="form-group form__item{{ $errors->has('card') ? ' form__item--error' : '' }} small-select">
                            <label for="card_id" class="col-sm-4 control-label">Карта: </label>
                            <div class="col-sm-8">
                                <select name="card_id" id="card" class="form-control js-select">
                                    @foreach ($cards as $card)<option value="{{ $card->id }}" @if ($token->card_id == $card->id) selected @endif title="{{ $card->currency }}">...{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} ({{ $card->currency }}) {{ $card->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
                            @endif
                        </div>

                        <div class="form-group form-group form__item{{ $errors->has('action') ? ' form__item--error' : '' }}">
                            <label for="action" class="col-sm-4 control-label">Действие</label>
                            <div class="col-sm-8">
                                <select name="action" id="action" class="form-control">
                                    <option value="deposit"  @if ($token->action === 'deposit')  selected @endif >Пополнить</option>
                                    <option value="withdraw" @if ($token->action === 'withdraw') selected @endif >Списать</option>
                                    <option value="transfer" @if ($token->action === 'transfer') selected @endif >Перевести</option>
                                </select>
                            </div>
                            @if ($errors->has('action'))
                                <p>{{ $errors->first('action') }}</p>
                            @endif
                        </div>

                        <div class="second_card form-group form__item{{ $errors->has('card') ? ' form__item--error' : '' }} small-select">
                            <label for="card2_id" class="col-sm-4 control-label">Куда перевести?</label>
                            <div class="col-sm-8">
                                <select name="card2_id" id="card2_id" class="form-control js-select" style="width: 100%;">
                                    @foreach ($cards as $card)<option value="{{ $card->id }}" @if ($token->card2_id == $card->id) selected @endif title="{{ $card->currency }}">...{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} ({{ $card->currency }}) {{ $card->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-5 col-sm-offset-0 col-xs-8 col-xs-offset-2">
                        <div class="form-group form__item{{ $errors->has('ask') ? ' form__item--error' : '' }}">
                            <div class="col-sm-12">
                                <label for="ask" class="control-label">Описание</label>
                                <textarea name="ask" id="ask" class="form-control" cols="80" rows="5" placeholder="краткий комментарий. не обязательно">{{$token->ask}}</textarea>
                                @if ($errors->has('value'))
                                    <p>{{ $errors->first('value') }}</p>
                                @endif
                            </div>
                        </div>

                        @if ( Auth::user()->status === 'admin' || Auth::user()->status === 'accountant' )
                        <div class="form-group form__item{{ $errors->has('ans') ? ' form__item--error' : '' }}">
                            <div class="col-xs-12">
                                <label for="ans" class="control-label">Отзыв</label>
                                <textarea name="ans" id="ask" class="form-control" cols="80" rows="5" placeholder="отзыв бухгалтера. не обязательно">{{$token->ans}}</textarea>
                                @if ($errors->has('ans'))
                                    <p>{{ $errors->first('ans') }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4 well @if ($token->status == 'confirmed') success @endif">
                        @if (Auth::user()->status == 'admin' || Auth::user()->status == 'accountant')
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="status" class="control-label col-xs-3">Статус</label>
                            <div class="col-xs-9">
                                <select name="status" id="status" class="form-control">
                                    @foreach ($statuses as $status)
                                        @if ($status == $token->status)
                                            <option value="{{$status}}" selected>{{ucfirst($status)}}</option>
                                        @else
                                            <option value="{{$status}}">{{ucfirst($status)}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else
                        <div class="text-center">
                            <label for="status">Статус: {{$token->status}}</label>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="row text-center">

                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                    </button>

                    <button class="btn btn-link" type="submit">
                        <a href="{{url('home/tokens')}}">Назад</a>
                    </button>

                </div>

            </form>

        </div>
        <!-- end items -->
    </main>
    <!-- end main -->

@endsection
<!-- end content -->

@section('scripts_end')
    <script>
        $(document).ready(function () {

            let selected_val = $('select#action').val();
            if (selected_val == 'transfer') {
                $('div.second_card').show();
            }

            $('select#action').on('change', function() {
                console.log('hi');
                let selected_val = $(this).val();
                if (selected_val == 'transfer') {
                    $('div.second_card').slideDown();
                } else {
                    $('div.second_card').slideUp();
                }
            });

        })
    </script>
@endsection