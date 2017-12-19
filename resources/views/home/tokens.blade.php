@extends('layouts.app')


@section('styles')
<style>
    .dropdown-menu {
        min-width: 0 !important
    }
</style>
@endsection


@section('content')

    <!-- begin header -->
    @section('page-name') Токены @endsection
    @include('layouts.headers.home')
    <!-- end header -->


    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <!-- begin items -->
            <div class="items">

                @if (Auth::user()->status !== 'accountant')
                <!-- begin items__add -->
                <div class="items__add">
                    <form class="form" method="POST" action="{{ url('/home/tokens') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить токен</h2>
                        </header>

                        <div class="form__item{{ $errors->has('card') ? ' form__item--error' : '' }}">
                            <label for="card">Карта</label>
                            <select name="card" id="card">
                                @foreach ($cards as $card)
                                    <option value="{{ $card->id }}" title="{{ $card->currency }}">
                                        {{ $card->name }} 
                                        ({{ $card->currency }}) 
                                        *{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} 
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('action') ? ' form__item--error' : '' }}">
                            <label for="action">Действие</label>
                            <select name="action" id="action">
                                <option value="deposit" >Пополнить</option>
                                <option value="withdraw">Списать</option>
                            </select>
                            @if ($errors->has('action'))
                                <p>{{ $errors->first('action') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('value') ? ' form__item--error' : '' }}">
                            <label for="value">Количество денег</label>
                            <input id="value" class="money_input" type="number" step="0.01" name="value">
                            @if ($errors->has('value'))
                                <p>{{ $errors->first('value') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('ask') ? ' form__item--error' : '' }}">
                            <label for="ask">Описание</label><br>
                            <textarea name="ask" id="ask" cols="50" rows="5" placeholder="краткий комментарий. не обязательно"></textarea>
                            @if ($errors->has('value'))
                                <p>{{ $errors->first('value') }}</p>
                            @endif
                        </div>

                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                            </button>
                        </div>

                    </form>
                </div>
                <!-- end items__add -->
                @endif

                <div class="items__list">
                    <h2>Список токенов</h2>
                    <form class="js-form" action="#" method="post">
                        <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="table-responsive">
                            <table class="table" id="tokens_list">
                                <thead>
                                    <tr>
                                        <td>Дата</td>
                                        @if (Auth::user()->status === 'admin' || Auth::user()->status === 'accountant')
                                        <td>Пользователь</td>
                                        @endif
                                        <td>Карта (CW2)</td>
                                        <td>Сумма</td>
                                        <td>Валюта</td>
                                        <td>Действие</td>
                                        <td>Описание</td>
                                        <td>Отзыв</td>
                                        <td>Статус</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tokens as $token)
                                        @if ( $token->status === 'confirmed' )
                                        <tr class="confirmed">
                                        @elseif ( $token->status === 'trash' )
                                        <tr class="trash">
                                        @endif
                                            <td>{{$token->date}}</td>
                                            @if (Auth::user()->status === 'admin' || Auth::user()->status === 'accountant')
                                            <td>{{$token->user_name}}</td>
                                            @endif
                                            <td>
                                                {{
                                                    substr($token->card_code, 0, 4).' '.
                                                    substr($token->card_code, 4, 4).' '.
                                                    substr($token->card_code, 8, 4).' '.
                                                    substr($token->card_code, 12, 4)
                                                }} 
                                                ({{ $token->card_cw2 }})
                                            </td>
                                            <td>{{html_entity_decode(number_format($token->value, 2, ',', "&nbsp;"))}}</td>
                                            <td>{{$token->currency}}</td>
                                            <td>{{$token->action}}</td>
                                            <td>{{$token->ask}}</td>
                                            <td>{{$token->ans}}</td>
                                            <td>
                                            @if (( Auth::user()->status === "admin" || Auth::user()->status === "accountant" ))
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{$token->status}}<span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="{{url('/home/tokens/')}}/{{$token->id}}/edit?status=active">active</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('/home/tokens/')}}/{{$token->id}}/edit?status=confirmed">confirmed</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('/home/tokens/')}}/{{$token->id}}/edit?status=trash">trash</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @else
                                                {{$token->status}}
                                            @endif
                                            </td>
                                            <td>
                                                @if (($token->status === 'active') || ( Auth::user()->status === "admin" || Auth::user()->status === "accountant" ))
                                                    <a href="{{url('/home/tokens/')}}/{{$token->id}}">
                                                        <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                                                    </a>
                                                    <a class='remove-btn' href="{{url('/home/tokens/')}}/{{$token->id}}">
                                                        <i class='remove fa fa-times fa-lg' title='Удалить' aria-hidden='true'></i>
                                                    </a>
                                                @else
                                                    -/-
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

            </div>
            <!-- end items -->

        </div>
    </main>
    <!-- end main -->


    <!-- Modal begin -->
    <div class="modal fade" id="modal_window" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal end -->

@endsection