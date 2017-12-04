@extends('layouts.app')


@section('styles')
    <style>
        .ui-datepicker-calendar, .ui-datepicker-current {
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
@endsection


@section('content')


    <!-- begin header -->
    @section('page-name') Карты @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <!-- begin items -->
            <div class="items">

                <!-- begin items__add -->
                <div class="items__add">
                    <form class="form" id='add-card' method="POST" action="{{ url('/home/cards') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить карту</h2>
                            <a href="{{url('home/multiple')}}">Добавить несколько карт</a>
                        </header>

                        <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                            <label for="name">Название</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Чемпионская">
                            @if ($errors->has('name'))
                                <p>{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('code') ? ' form__item--error' : '' }}">
                            <label for="code">Номер карты</label>
                            <input id="code" type="text" name="code" placeholder="16 digits only" maxlength="16" required>
                            @if ($errors->has('code'))
                                <p>{{ $errors->first('code') }}</p>
                            @endif
                            @if ($errors->has('card_code'))
                                <p>{{ $errors->first('card_code') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('cw2') ? ' form__item--error' : '' }}">
                            <label for="cw2">CW2</label>
                            <input id="cw2" type="text" name="cw2" placeholder="xxx" maxlength="3" required>
                            @if ($errors->has('cw2'))
                                <p>{{ $errors->first('cw2') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('date') ? ' form__item--error' : '' }}">
                            <label for="date">Дата</label>
                            <input id="date" class="card_date" type="text" name="date" value="{{ old('date') }}" placeholder="Введите дату" required>
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
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('date'))
                                <p>{{ $errors->first('user') }}</p>
                            @endif
                        </div>

                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-credit-card-alt"></i> Добавить
                            </button>
                        </div>

                    </form>
                </div>
                <!-- end items__add -->

                <!-- begin items__list -->
                <div class="items__list">
                    <h2>Список карт</h2>
                    <form class="js-form" action="{{url('/home/cards/multiple')}}" method="post">

                        <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">

                        <!-- begin table -->
                        <table class="table js-table" id="all-cards">
                            <thead>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="all_select">
                                    </td>
                                    <!-- <td>id</td> -->
                                    <td>Название</td>
                                    <td>Код</td>
                                    <td>Валюта</td>
                                    <td>Дата</td>
                                    <td>Статус</td>
                                    <td>Пользователь</td>
                                    <td>Действия</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cards as $card)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="shift_select" name='card[{{ $card->id }}]'>
                                        </td>
                                        <!-- <td>{{ $card->id }}</td> -->
                                        <td>{{ $card->name }}</td>
                                        <td>{{ $card->code }}</td>
                                        <td>{{ $card->currency }}</td>
                                        <td>{{ substr($card->date, 0, 7) }}</td>
                                        <td>{{ $card->status }}</td>
                                        <td class="card_user">
                                            <a href="{{ url('/home/cards/') }}/{{ $card->id }}">
                                                <span>{{ $card->user_name }}</span>
                                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td class="card-actions">
                                            <a href="{{ url('/home/cards/') }}/{{ $card->id }}/edit">
                                                @if ($card->status === 'active')
                                                <i class="switch fa fa-toggle-on fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>
                                                @else
                                                <i class="switch fa fa-toggle-off fa-lg" title="Вкл/Выкл" aria-hidden="true"></i>
                                                @endif
                                            </a>
                                            <a class="remove-btn" href="{{ url('/home/cards/') }}/{{ $card->id }}">
                                                <i class="remove fa fa-times fa-lg" title="Удалить" aria-hidden="true"></i>
                                            </a>
                                            <!-- <form method="post" action="{{ url('/home/cards/') }}/{{ $card->id }}">
                                                <input type="hidden" name="_method" value="delete">
                                                <i class="remove fa fa-times fa-lg" title="Удалить" aria-hidden="true"></i>
                                            </form> -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- end table -->

                        <!-- begin select-action -->
                        <span>Действие:</span>
                        <select name="" id="">
                            <option value="1">Назначить пользователя</option>
                            <option value="2">Изменить статус</option>
                            <option value="3">Удалить</option>
                        </select>
                        <button type="submit">Выполнить</button>
                        <!-- end select-action -->

                    </form>
                </div>
                <!-- end items__list -->

            </div>
            <!-- end items -->

        </div>
    </main>
    <!-- end main -->

@endsection