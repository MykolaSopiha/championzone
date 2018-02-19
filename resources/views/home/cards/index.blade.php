@extends('layouts.app')


@section('styles')
    <style>
        .ui-datepicker-calendar, .ui-datepicker-current {
            display: none !important;
        }

        #all_cards tfoot {
            display: table-header-group;
        }

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
@section('page-name') Карты @endsection
@include('layouts.headers.home')
<!-- end header -->


<!-- begin main -->
<main class="main" role="main">
    <div class="main-inner">

        <!-- begin items -->
        <div class="items">

        @if (Auth::user()->status != 'mediabuyer')
            <!-- begin items__add -->
                <div class="items__add">
                    <form class="form" id='add-card' method="POST" action="{{ url('/home/cards') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить карту</h2>
                        </header>

                        <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                            <label for="name">Название</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                   placeholder="Чемпионская">
                            @if (!$errors->has('name'))
                                <p class="text-warning">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('type') ? ' form__item--error' : '' }}">
                            <label>Тип</label>
                            <div class="card_type">
                                <input type="radio" name="type" value="0" required>&nbsp;Яндекс.Деньги<br>
                                <input type="radio" name="type" value="1" required>&nbsp;QIWI<br>
                                <input type="radio" name="type" value="2" required>&nbsp;Пластиковая карта
                            </div>
                            @if ($errors->has('type'))
                                <p>{{ $errors->first('type') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('code_hash') ? ' form__item--error' : '' }}">
                            <label for="code">Номер карты</label>
                            <input id="code" type="text" name="code" placeholder="16 digits only" required>
                            @if ($errors->has('code_hash'))
                                <p>{{ $errors->first('code_hash') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('wallet') ? ' form__item--error' : '' }}">
                            <label for="wallet">Номер кошелька</label>
                            <input id="wallet" type="text" name="wallet" value="{{ old('wallet') }}"
                                   placeholder="only numbers">
                            @if ($errors->has('wallet'))
                                <p>{{ $errors->first('wallet') }}</p>
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
                            <input id="date" class="card_date" type="text" name="date"
                                   value="{{ substr(old('date'), 0, -2) }}" placeholder="Введите дату" required>
                            @if ($errors->has('date'))
                                <p>{{ $errors->first('date') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('currency') ? ' form__item--error' : '' }} big-select">
                            <label for="currency">Валюта</label>
                            <select name="currency" id="currency" class="js-select">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="RUB">RUB</option>
                                <option value="UAH">UAH</option>
                            </select>
                            @if ($errors->has('currency'))
                                <p>{{ $errors->first('currency') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('user') ? ' form__item--error' : '' }} big-select">
                            <label for="user">Пользователь</label>
                            <select name="user" id="user" class="js-select">
                                @foreach ($users as $user)
                                    @if ($user->first_name == "" || $user->last_name == "")
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->first_name." ".$user->last_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('user'))
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
        @endif

        <!-- begin items__list -->
            <div class="items__list">
                @if (Auth::user()->status != 'mediabuyer')
                    <h2>Список карт</h2>
                @endif

                <div class="filter" style="margin-bottom: 0px;">
                    <form class="form-inline" method="get">

                        @if (Auth::user()->status === 'admin' || Auth::user()->status === 'accountant')
                            <div class="form-group small-select" style="max-width: 300px">
                                <label for="user">Пользователь</label><br>
                                <select name="user_id" id="user" class="js-select form-control">
                                    <option value="">Все пользователи</option>
                                    <option value="0"
                                            @if (isset($_GET['user_id']) && $_GET['user_id'] == '0') selected @endif>
                                        Назначить пользователя
                                    </option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}" {{(isset($_GET['user_id']) && $user->id == $_GET['user_id'])? "selected" : "" }}>{{$user->first_name." ".$user->last_name." ".$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group small-select" style="max-width: 400px">
                            <label for="card">Код</label><br>
                            <select name="id" id="card" class="js-select form-control">
                                <option value="">Все карты</option>
                                @foreach ($cards as $card)
                                    <option value="{{$card->id}}"
                                            @if (isset($_GET['id']) && $card->id == $_GET['id']) selected @endif>
                                        ...{{substr($card->code, -8, -4)." ".substr($card->code, -4)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="max-width: 400px">
                            <label for="type">Тип</label><br>
                            <select name="type" id="type" class="form-control">
                                <option value="">Все</option>
                                @foreach ($card_types as $key => $val)
                                    <option value="{{$key}}"
                                            @if (isset($_GET['type']) && $_GET['type'] != '' && $_GET['type'] == $key) selected="selected" @endif>
                                        {{ucfirst($val)}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="max-width: 400px">
                            <label for="currency">Валюта</label><br>
                            <select name="currency" id="currency" class="form-control">
                                <option value="">Все</option>
                                @foreach ($currencies as $c)
                                    <option value="{{$c}}"
                                            @if (isset($_GET['currency']) && $_GET['currency'] == $c) selected="selected" @endif>{{$c}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-top: 24px; margin-left: 20px">
                            <button type="submit" class="btn btn-primary">Искать</button>
                            <button type="submit" class="btn btn-default">
                                <a href="{{url('home/cards')}}">Сбросить</a>
                            </button>
                        </div>
                    </form>
                </div>

                <br/>

                <form class="js-form" action="{{url('/home/cards/multiple_action')}}" method="post">

                @if (Auth::user()->status != 'mediabuyer')
                    <!-- begin select-action -->
                        <div class="select-action">
                            <span>Действие:</span>
                            <select class="js-action" name="card_action">
                                <option selected="">--</option>
                                <option value="1">Назначить пользователя</option>
                                <option value="2">Активировать</option>
                                <option value="3">Заморозить</option>
                                <option value="4">Удалить</option>
                            </select>
                            <select class="js-users" name="card_user">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <button class="js-submit" type="submit">Выполнить</button>
                        </div>
                        <!-- end select-action -->
                    @endif

                    <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">

                    <!-- begin table -->
                    <div class="table-responsive">
                        <table class="table" id="all_cards" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th><input type="checkbox" class="all_select"></th>
                                <th>Название</th>
                                <th>Код</th>
                                <th>Валюта</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th>Пользователь</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- end table -->

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
        $(document).ready(function () {

            $('#code').change(function () {
                let trimmed_code = $(this).val();
                trimmed_code = $.trim(trimmed_code);
                trimmed_code = trimmed_code.replace(/ /g, '');
                trimmed_code = trimmed_code.substring(0, 16);
                $(this).val(trimmed_code);
            });

            $('#all_cards').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "{{url('/api/cards')}}",
                    data: {
                        data: window.location.search.toString().substr(1)
                    }
                },
                "lengthMenu": [10, 25, 50, 75, 100, 200, 500],
                "responsive": true,
                "searching": false,
                "ordering": true,
                "columns": [
                    {data: 'check', name: 'action', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'code'},
                    {data: 'currency'},
                    {data: 'date'},
                    {data: 'status'},
                    {data: 'user_id'},
                    {data: 'actions'},
                    {data: 'type'}
                ],
                "columnDefs": [
                    {
                        @if ((Auth::user()->status == 'admin' || Auth::user()->status == 'accountant'))
                        "targets": [8],
                        @elseif (Auth::user()->TeamLead())
                        "targets": [0, 1, 8],
                        @else
                        "targets": [0, 1, 6, 8],
                        @endif
                        "visible": false,
                        "searchable": true
                    },
                    {
                        "targets": [5, 7],
                        "orderable": false
                    }
                ],
                "initComplete": function () {
                    let table = this;
                    @if (Auth::user()->status == 'admin' || Auth::user()->status == 'accountant')
                    table.api().column(2).every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                    });
                            @endif

                    let $chkboxes = $('.shift_select');
                    let lastChecked = null;
                    $chkboxes.click(function (e) {
                        if (!lastChecked) {
                            lastChecked = this;
                            return;
                        }
                        if (e.shiftKey) {
                            let start = $chkboxes.index(this);
                            let end = $chkboxes.index(lastChecked);
                            $chkboxes.slice(Math.min(start, end), Math.max(start, end) + 1).prop('checked', lastChecked.checked);
                        }
                        lastChecked = this;
                    });


                    // BEGIN Select card type
                    let $card_types_btn = $('.js_card_types');
                    let $curr_card_type = $card_types_btn.find('.type_name');
                    let $card_types = $card_types_btn.find('.type');

                    $card_types.on('click', function (e) {
                        e.preventDefault();
                        let type = $(this).html();
                        let code = $(this).attr('data-type-code');
                        $curr_card_type.html(type);
                        table.api().column(8).search(code).draw();
                    });
                    // END Select card type
                }
            });

        });
    </script>
@endsection