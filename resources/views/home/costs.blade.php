@extends('layouts.app')

@section('content')

    <!-- begin header -->
    @section('page-name') Расходы @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <!-- begin items -->
            <div class="items">

                <!-- begin items__add -->
                <div class="items__add">
                    <form class="form" method="POST" action="{{ url('/home/costs') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить запись</h2>
                        </header>

                        <div class="form__item{{ $errors->has('date') ? ' form__item--error' : '' }}">
                            <label for="date">Дата</label>
                            <input id="date" class="pick_date" type="text" name="date" placeholder="Введите дату" required>
                            @if ($errors->has('date'))
                                <p>{{ $errors->first('date') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('card') ? ' form__item--error' : '' }}">
                            <label for="card">Карта</label>
                            <select name="card" id="card">
                                @foreach ($cards as $card)
                                    @if ($card->status === 'active')
                                    <option value="{{ $card->id }}" title="{{ $card->currency }}">{{ $card->name }} ({{ $card->currency }}) *{{ substr(decrypt($card->code), -8, -4).' '.substr(decrypt($card->code), -4) }} </option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('value') ? ' form__item--error' : '' }}">
                            <label for="value">Потрачено денег</label>
                            <input id="value" class="money_input" type="number" step="0.01" min="0" name="value" value="{{ old('value') }}" required>
                            @if ($errors->has('value'))
                                <p>{{ $errors->first('value') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('rate') ? ' form__item--error' : '' }}">
                            <label for="rate">Курс относительно USD</label>
                            <input id="rate" type="number" step="0.000001" min="0" name="rate" value="{{ old('rate') }}" required>
                            @if ($errors->has('rate'))
                                <p>{{ $errors->first('rate') }}</p>
                            @endif
                        </div>

                        <div class="form__item">
                            <button type="button" id="get_rate">
                                <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Обновить курс
                            </button>
                        </div>

                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Добавить
                            </button>
                        </div>

                    </form>
                </div>
                <!-- end item__add -->


                <div class="items__list">
                    <h2>Список затрат</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Дата</td>
                                <td>Карта</td>
                                <td>Объем</td>
                                <td>Курс</td>
                                <td>Пользователь</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($costs as $cost)
                                <tr>
                                    <td>{{ $cost->date }}</td>
                                    <td>{{ $cost->card_name }} ({{ $cost->currency }}) *{{ substr($cost->card_code, -4) }}</td>
                                    <td>{{ number_format($cost->value, 2, ',', ' ') }}</td>
                                    <td>{{ number_format($cost->rate,  6, ',', ' ') }}</td>
                                    <td>{{ $cost->user_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- end items -->

        </div>
    </main>
    <!-- end main -->

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
@endsection