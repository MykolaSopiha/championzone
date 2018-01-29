@extends('layouts.app')


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
                    <form class="form" method="POST" action="{{url('/home/costs')}}">

                        {{csrf_field()}}

                        <input id="rate" type="hidden" class="readonly" name="rate" value="{{old('rate')}}" readonly required>

                        <header class="form__header">
                            <h2>Добавить запись</h2>
                            @if (Auth::user()->status == 'admin') <a href="{{url('/home/costtypes')}}">Добавить статьи расходов</a> @endif
                        </header>

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
                                            <td>{{$cost->cost_type_name}}</td>
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