@extends('layouts.app')


@section('styles')
    <style>
        .dropdown-menu {
            min-width: 0 !important
        }

        .chosen-js .chosen-container {
            font-size: 18px !important;
            width: 100% !important;
        }

        .chosen-js .chosen-single {
            height: 70px !important;
            line-height: 70px !important;
            background: none !important;
            text-align: center
        }

        #tokens_ssp_table tfoot {
            display: table-header-group;
        }

        .chosen-single {
            height: 34px !important;
            line-height: 34px !important;
            background: none !important;
            min-width: 150px;
        }

        .chosen-container {
            min-width: 150px;
        }

        .transfer_dest, .has_wallet {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
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

            <!-- begin items__add -->
            <div class="items__add">
                <form class="form new-token" method="POST" action="{{ url('/home/tokens') }}">

                    {{ csrf_field() }}

                    <header class="form__header">
                        <h2>Добавить токен</h2>
                    </header>

                    @if (Auth::user()->status == 'accountant' || Auth::user()->status == 'admin')
                        <div class="form__item{{ $errors->has('date') ? ' form__item--error' : '' }}">
                            <label for="date">Дата</label>
                            <input id="date" class="pick_date" type="text" name="date" placeholder="Введите дату"
                                   value="{{date('Y-m-d')}}" required>
                            @if ($errors->has('date'))
                                <p>{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                    @endif

                    @if (Auth::user()->status == 'accountant' || Auth::user()->status == 'admin' || Auth::user()->TeamLead())
                        <div class="form__item{{ $errors->has('card') ? ' form__item--error' : '' }} big-select">
                            <label for="user_id">Пользователь</label>
                            <select name="user_id" id="user_id" class="js-select">
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}" @if ($user->id == Auth::user()->id) selected @endif>
                                        {{$user->first_name." ".$user->last_name." ".$user->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="form__item{{ $errors->has('card') ? ' form__item--error' : '' }} big-select">
                        <label for="card">Карта</label>
                        <select name="card_id" id="card" class="js-select">
                            @foreach ($cards as $card)
                                <option value="{{$card->id}}" title="{{$card->currency}}">
                                    ... {{substr($card->code, -8, -4)." ".substr($card->code, -4)}} ({{$card->currency}}
                                    ) {{$card->name}}
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
                            <option value="deposit">Пополнить</option>
                            <option value="withdraw">Списать</option>
                            <option value="transfer">Перевести</option>
                        </select>
                        @if ($errors->has('action'))
                            <p>{{ $errors->first('action') }}</p>
                        @endif
                    </div>

                    <div class="second_card form__item{{ $errors->has('card') ? ' form__item--error' : '' }} big-select">
                        <label for="card">Куда перевести?</label>
                        <select name="card2_id" id="card2" class="js-select" style="width: 100%;">
                            @foreach ($cards as $card)
                                <option value="{{ $card->id }}" title="{{ $card->currency }}">
                                    ...{{ substr($card->code, -8, -4)." ".substr($card->code, -4) }}
                                    ({{ $card->currency }}) {{ $card->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('card'))
                            <p>{{ $errors->first('card') }}</p>
                        @endif
                    </div>

                    <div class="form__item{{ $errors->has('value') ? ' form__item--error' : '' }}">
                        <label for="value">Количество денег</label>
                        <input id="value" class="money_input" type="text" step="0.01" name="value" required>
                        @if ($errors->has('value'))
                            <p>{{ $errors->first('value') }}</p>
                        @endif
                    </div>

                    <div class="form__item{{ $errors->has('rate') ? ' form__item--error' : '' }}">
                        <label for="rate">Курс относительно USD</label>
                        <input id="rate" class="readonly" type="number" step="0.000001" min="0" name="rate"
                               value="{{ old('rate') }}" readonly required>
                        @if ($errors->has('rate'))
                            <p>{{ $errors->first('rate') }}</p>
                        @endif
                    </div>

                    <div class="form__item">
                        <button type="button" id="get_rate" class="btn btn-primary">
                            <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Обновить курс
                        </button>
                    </div>

                    <div class="form__item{{ $errors->has('ask') ? ' form__item--error' : '' }}">
                        <label for="ask">Описание</label><br>
                        <textarea name="ask" id="ask" cols="50" rows="5" style="width: 100%;"
                                  placeholder="краткий комментарий. не обязательно"></textarea>
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

            <div class="items__list">
                <h2>Список токенов</h2>

                <div style="margin-bottom: 50px;">
                    <form class="form-inline" method="get">

                        <div class="form-group">
                            <label for="filter_date">Дата</label><br>
                            @if (isset($_GET['date']))
                                <input type="text" name="date" value="{{$_GET['date']}}" class="form-control"
                                       id="filter_date">
                            @else
                                <input type="text" name="date" class="form-control" id="filter_date">
                            @endif
                        </div>

                        @if (Auth::user()->status === 'admin' || Auth::user()->status === 'accountant' || Auth::user()->TeamLead())
                            <div class="form-group small-select">
                                <label for="user">Пользователь</label><br>
                                <select name="user_id" id="user" class="js-select form-control">
                                    <option value="">Все пользователи</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}"
                                                @if (isset($_GET['user_id']) && $user->id == $_GET['user_id']) selected @endif>{{$user->first_name}} {{$user->name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group small-select" style="max-width: 400px">
                            <label for="filter_card">Карта</label><br>
                            <select name="card_id" id="filter_card" class="js-select form-control">
                                <option value="">Все карты</option>
                                @foreach ($cards as $card)
                                    <option value="{{$card->id}}"
                                            @if (isset($_GET['card_id']) && $card->id == $_GET['card_id']) selected @endif>
                                        ...{{substr($card->code, -8, -4)." ".substr($card->code, -4)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="max-width: 400px">
                            <label for="status">Статус</label><br>
                            <select name="status" id="status" class="form-control">
                                <option value="">Все</option>
                                @foreach ($statuses as $s)
                                    <option value="{{$s}}"
                                            @if (isset($_GET['status']) && $_GET['status'] == $s) selected="selected" @endif>{{ucfirst($s)}}</option>
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
                                <a href="{{url('home/tokens')}}">Сбросить</a>
                            </button>
                        </div>
                    </form>
                </div>
                <div>
                    <form class="js-form" action="#" method="post">
                        <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="table-responsive">
                            <table class="table" id="tokens_ssp_table">
                                <thead>
                                <tr>
                                    <td>Дата</td>
                                    <td>Пользователь</td>
                                    <td>Карта</td>
                                    <td>Сумма</td>
                                    <td>Валюта</td>
                                    <td>Курс</td>
                                    <td>Действие</td>
                                    <td>Описание</td>
                                    <td>Отзыв</td>
                                    <td>Статус</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end items -->
    </div>
</main>
<!-- end main -->

<!-- begin beep -->
<div class="beep" style="visibility: hidden">
    <audio id="sound1">
        <source src="{{ url('audio/filling-your-inbox.mp3') }}">
    </audio>
</div>
<!-- end beep -->


<!-- Modal for transfer destination card -->
<div class="modal fade" id="transferDest" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4></h4>
            </div>
            <div class="modal-body text-center" style="padding:20px"></div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary pull-left" data-dismiss="modal">Закрыть</button>
            </div>
        </div>

    </div>
</div>
<!-- Modal for transfer destination card -->

<!-- Modal for transfer destination card -->
<div class="modal fade" id="newTicket" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Проверьте данные</h4>
            </div>
            <div class="modal-body text-center" style="padding:20px"></div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary pull-left" data-dismiss="modal">Закрыть</button>
            </div>
        </div>

    </div>
</div>
<!-- Modal for transfer destination card -->

@endsection


@section('scripts_end')
    <script>
        $(document).ready(function () {

            $('#filter_date').datepicker({
                dateFormat: "yy-mm-dd",
                changeDay: true,
                changeMonth: true,
                changeYear: true,
                yearRange: "-5:+5",
                showButtonPanel: false
            });

            $('select#action').on('change', function () {
                let selected_val = $(this).val();

                if (selected_val == 'transfer') {
                    $('div.second_card').slideDown();
                } else {
                    $('div.second_card').slideUp();
                }
            });

            let user_status = {{(Auth::user()->status == 'admin' || Auth::user()->status == 'accountant' || Auth::user()->TeamLead()) ? "true" : "false"}};
            let columnDefs_json = {};

            if (!user_status) {
                columnDefs_json = {
                    "targets": [1],
                    "visible": false,
                    "searchable": true
                };
            }


            let $table = $('#tokens_ssp_table').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    url: "{{url('/api/tokens')}}",
                    data: {
                        data: window.location.search.toString().substr(1)
                    }
                },
                "responsive": true,
                "ordering": false,
                "lengthMenu": [10, 25, 50, 75, 100, 200, 500],
                "columns": [
                    {data: 'date'},
                    {data: 'user_name'},
                    {data: 'card_code'},
                    {data: 'value'},
                    {data: 'currency'},
                    {data: 'rate'},
                    {data: 'action'},
                    {data: 'ask'},
                    {data: 'ans'},
                    {data: 'status'},
                    {data: 'tools'}
                ],
                "columnDefs": [columnDefs_json],
                "initComplete": function () {
                },
                "drawCallback": function (settings) {

                    $('.transfer_dest').on('click', function (e) {
                        let dest_card = $(this).attr('data-card-code');
                        $('.modal-body').html("<h2>" + dest_card + "</h2>");
                        $('.modal-header h4').html('Перевести деньги на карту:');
                        $('#transferDest').modal('show');
                    });

                    $('.has_wallet').on('click', function (e) {
                        let wallet = $(this).attr('data-wallet-code');
                        $('.modal-body').html("<h2>" + wallet + "</h2>");
                        $('.modal-header h4').html('Номер кошелька:');
                        $('#transferDest').modal('show');
                    });

                    $(".token_status").each(function (index) {
                        if ($(this).html() == 'confirmed') {
                            $(this).closest('tr').css('background-color', 'rgb(144, 238, 144)');
                        }
                        if ($(this).html() == 'trash') {
                            $(this).closest('tr').css('background-color', 'rgb(250, 128, 114)');
                        }
                    });

                }
            });

            const BEEP = (soundObj) => {
                let sound = document.getElementById(soundObj);
                if (sound)
                    sound.play();
            }

            var tokens_count = null;
            let checkTokensUrl = "{{url('/api/token_notify')}}?user_id={{Auth::user()->id}}&user_status={{Auth::user()->status}}";

            @if ($user = Auth::user())
            const checkTokens = () => {
                    $.ajax({
                        url: checkTokensUrl,
                        type: 'GET',
                        success: function (result) {

                            if (tokens_count == null) {
                                tokens_count = result;
                                return;
                            }

                            console.log(result);

                            if (tokens_count < result) {
                                BEEP("sound1");
                                alert("Новый токен!");
                            }

                            if (tokens_count > result) {
                                BEEP("sound1");
                                $table.draw();
                                alert("Токен обработан!");
                            }

                            tokens_count = result;
                        }
                    });
                }
            checkTokens();
            setInterval(checkTokens, 180000);
            @endif
        });
    </script>
@endsection