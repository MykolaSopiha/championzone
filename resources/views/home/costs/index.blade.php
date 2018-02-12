@extends('layouts.app')


@section('styles')
    <style>
        .chosen-single {
            height: 70px !important;
            line-height: 70px !important;
            background: none !important;
            min-width: 150px;
            font-size: 18px;
            text-align: center;
            border-radius: 10px !important;
        }
        .chosen-container {
            min-width: 150px;
        }
        .filter .chosen-single {
            height: 34px !important;
            line-height: 34px !important;
            background: none !important;
            min-width: 150px;
            font-size: 14px;
            text-align: left;
            borer-radius: 4px !important;
        }
    </style>
@endsection





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
                    <form class="form" method="POST" action="{{url('/home/costs')}}">

                        {{csrf_field()}}

                        <input id="rate" type="hidden" class="readonly" name="rate" value="{{old('rate')}}" readonly required>

                        <header class="form__header">
                            <h2>Добавить запись</h2>
                            @if (Auth::user()->status == 'admin' || Auth::user()->status == 'accountant') <a href="{{route('home:cost.types.index')}}">Добавить статьи расходов</a> @endif
                        </header>

                        @if (Auth::user()->status == 'accountant')
                        <div class="form__item{{ $errors->has('user_id') ? ' form__item--error' : '' }}">
                            <label for="user">Пользователь</label>
                            <select name="user_id" id="user" class="chosen-js-select">
                                @foreach ($users as $user)
                                    @if ($user->first_name == "" || $user->last_name == "")
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->first_name." ".$user->last_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('user_id'))
                                <p>{{ $errors->first('user_id') }}</p>
                            @endif
                        </div>
                        @endif

                        <div class="form__item{{ $errors->has('date') ? ' form__item--error' : '' }}">
                            <label for="date">Дата</label>
                            <input id="date" class="pick_date" type="text" name="date" placeholder="Введите дату" required>
                            @if ($errors->has('date'))
                                <p>{{ $errors->first('date') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('currency') ? ' form__item--error' : '' }}">
                            <label for="cost_type_id">Статья расходов</label>
                            <select name="cost_type_id" id="cost_type_id">
                                @foreach ($costtypes as $costtype)
                                    <option value="{{$costtype->id}}" title="{{$costtype->description}}">{{$costtype->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
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
                                        <td>Пользователь</td>
                                        <td>Назначение</td>
                                        <td>Объем</td>
                                        <td>Описание</td>
                                        <td>Пользователь</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($costs as $cost)
                                        <tr>
                                            <td>{{$cost->date}}</td>
                                            <td>{{$cost->user->name}}</td>
                                            <td>{{$cost->costType->name}}</td>
                                            <td>{{$cost->value}} ({{$cost->currency}})</td>
                                            <td>{{$cost->info}}</td>
                                            <td>{{$cost->user->name}}</td>
                                            <td><a href="{{route('home:home.costs.destroy', $cost->id)}}" class="remove-btn"><i class="fa fa-times" aria-hidden="true"></i></a></td>
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
            $('#costs_list').DataTable({
                "columnDefs": [{
                        "targets": [5],
                        "orderable": false
                }]
            });
        });
    </script>
@endsection