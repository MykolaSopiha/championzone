@extends('layouts.app')


@section('styles')
    <style>
        .ui-datepicker-calendar, .ui-datepicker-current {
            display: none !important;
        }
        .chosen-container {
            font-size: 18px !important
        }
        .chosen-single {
            height: 70px !important;
            line-height: 70px !important;
            background: none !important;
            text-align: center
        }
        #all_cards tfoot {
            display: table-header-group;
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

                        <div class="form__item{{ $errors->has('type') ? ' form__item--error' : '' }}">
                            <label>Тип</label>
                            <div class="card_type">
                                <input type="radio" name="type" value="0" required>Яндекс.Деньги<br>
                                <input type="radio" name="type" value="1" required>QIWI<br>
                                <input type="radio" name="type" value="2" required>Пластиковая карта
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

                        <div class="form__item{{ $errors->has('cw2') ? ' form__item--error' : '' }}">
                            <label for="cw2">CW2</label>
                            <input id="cw2" type="text" name="cw2" placeholder="xxx" maxlength="3" required>
                            @if ($errors->has('cw2'))
                                <p>{{ $errors->first('cw2') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('date') ? ' form__item--error' : '' }}">
                            <label for="date">Дата</label>
                            <input id="date" class="card_date" type="text" name="date" value="{{ substr(old('date'), 0, -2) }}" placeholder="Введите дату" required>
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
                            <select name="user" id="user" class="chosen-js-select">
                                @foreach ($users as $user)
                                    @if ($user->first_name == "" || $user->last_name == "")
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->first_name." ".$user->last_name }}</option>
                                    @endif
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

                    <div class="dropdown js_card_types">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                            <span class="type_name">Все карты</span>
                            &nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu type_list">
                            <li><a href="#" class="type" data-type-code="">Все карты</a></li>
                            <li><a href="#" class="type" data-type-code="0">Яндекс.Деньги</a></li>
                            <li><a href="#" class="type" data-type-code="1">QIWI</a></li>
                            <li><a href="#" class="type" data-type-code="2">Пластиковые карты</a></li>
                        </ul>
                    </div>

                    <form class="js-form" action="{{url('/home/cards/multiple_action')}}" method="post">

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
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
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
        $(document).ready(function() {

            $('#code').change(function () {
                let trimmed_code = $(this).val();
                trimmed_code = $.trim(trimmed_code);
                trimmed_code = trimmed_code.replace(/ /g,'');
                trimmed_code = trimmed_code.substring(0, 16);
                $(this).val(trimmed_code);
            });

            $('#all_cards').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{url('/api/cards')}}",
                "lengthMenu": [ 10, 25, 50, 75, 100, 200, 500 ],
                "responsive": true,
                "columns":[
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
                "columnDefs": [{
                    "targets": [8],
                    "visible": false,
                    "searchable": true
                }],
                "initComplete": function () {
                    let table = this;
                    table.api().column(2).every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });


                    let $chkboxes = $('.shift_select');
                    let lastChecked = null;
                    $chkboxes.click(function(e) {
                        if(!lastChecked) {
                            lastChecked = this;
                            return;
                        }
                        if(e.shiftKey) {
                            let start = $chkboxes.index(this);
                            let end = $chkboxes.index(lastChecked);
                            $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
                        }
                        lastChecked = this;
                    });


                    // BEGIN Select card type
                    let $card_types_btn = $('.js_card_types');
                    let $curr_card_type = $card_types_btn.find('.type_name');
                    let $card_types     = $card_types_btn.find('.type');

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