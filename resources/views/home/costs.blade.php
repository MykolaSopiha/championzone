@extends('layouts.app')


@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
@endsection


<!-- begin header -->
@section('page-name') Расходы @endsection
@include('layouts.headers.home')
<!-- end header -->


@section('content')
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

                        <div class="form__item{{ $errors->has('currency') ? ' form__item--error' : '' }}">
                            <label for="currency">Валюта</label>
                            <select name="currency" id="card">
                                @foreach ($currencies as $currency)
                                    <option value="{{$currency}}" title="{{$currency}}">{{$currency}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('value') ? ' form__item--error' : '' }}">
                            <label for="value">Потрачено денег</label>
                            <input id="value" class="money_input" name="value" value="{{old('value')}}" required>
                            @if ($errors->has('value'))
                                <p>{{ $errors->first('value') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('rate') ? ' form__item--error' : '' }}">
                            <label for="rate">Курс относительно USD</label>
                            <input id="rate" type="text" name="rate" value="{{ old('rate') }}" readonly required>
                            @if ($errors->has('rate'))
                                <p>{{ $errors->first('rate') }}</p>
                            @endif
                        </div>

                        <div class="form__item">
                            <button type="button" id="get_rate">
                                <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Обновить курс
                            </button>
                        </div>

                        <div class="form__item{{ $errors->has('info') ? ' form__item--error' : '' }}">
                            <label for="info">Описание</label>
                            <textarea name="info" id="info" cols="80" rows="10"></textarea>
                            @if ($errors->has('info'))
                                <p>{{ $errors->first('info') }}</p>
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
                    <h2>Список затрат</h2>
                    <form class="js-form" action="#" method="post">
                        <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="table-responsive">
                            <table class="table" id="costs_list">
                                <thead>
                                    <tr>
                                        <td>Дата</td>
                                        <td>Объем</td>
                                        <td>Курс</td>
                                        <td>Описание</td>
                                        <td>Пользователь</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($costs as $cost)
                                        <tr>
                                            <td>{{ $cost->date }}</td>
                                            <td>{{ number_format($cost->value, 2, ',', ' ') }}</td>
                                            <td>{{ number_format($cost->rate,  6, ',', ' ') }}</td>
                                            <td>{{ $cost->info }}</td>
                                            <td>{{ $cost->user_name }}</td>
                                            <td><a href="{{url('home/costs/').'/'.$cost->id}}" class="remove-btn"><i class="fa fa-times" aria-hidden="true"></i></a></td>
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


@section('scripts_end')
    <script>
        $(document).ready(function() {
            $('#costs_list').DataTable({});
        });
    </script>
@endsection