@extends('layouts.app')


@section('styles')
<style>
    .dropdown-menu {
        min-width: 0 !important
    }
    .chosen-js .chosen-container {
        font-size: 18px !important
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

                        <div class="chosen-js form__item{{ $errors->has('card') ? ' form__item--error' : '' }}">
                            <label for="card">Карта</label>
                            <select name="card" id="card" class="chosen-js-select">
                                @foreach ($cards as $card)<option value="{{ $card->id }}" title="{{ $card->currency }}">...{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} ({{ $card->currency }}) {{ $card->name }}</option>
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
                            <input id="value" class="money_input" type="text" step="0.01" name="value">
                            @if ($errors->has('value'))
                                <p>{{ $errors->first('value') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('rate') ? ' form__item--error' : '' }}">
                            <label for="rate">Курс относительно USD</label>
                            <input id="rate" class="readonly" type="number" step="0.000001" min="0" name="rate" value="{{ old('rate') }}" readonly required>
                            @if ($errors->has('rate'))
                                <p>{{ $errors->first('rate') }}</p>
                            @endif
                        </div>

                        <div class="form__item">
                            <button type="button" id="get_rate">
                                <i class="fa fa-refresh fa-lg" aria-hidden="true"></i> Обновить курс
                            </button>
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

                    <div style="margin-bottom: 50px;">
                        <form class="form-inline" method="get">

                            <div class="form-group" style="max-width: 300px">
                                <label for="date">Дата</label><br>
                                @if (isset($_GET['date']))
                                    <input type="text" name="date" value="{{$_GET['date']}}" class="form-control pick_date" id="date">
                                @else
                                    <input type="text" name="date" class="form-control pick_date" id="date">
                                @endif
                            </div>

                            @if (Auth::user()->status === 'admin' || Auth::user()->status === 'accountant')
                            <div class="form-group" style="max-width: 300px">
                                <label for="user">Пользователь</label><br>
                                <select name="user_id" id="user" class="chosen-js-select form-control">
                                    <option value="">Все пользователи</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}" @if (isset($_GET['user_id']) && $user->id == $_GET['user_id']) selected @endif>{{$user->first_name}} {{$user->name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="form-group" style="max-width: 400px">
                                <label for="card">Карта</label><br>
                                <select name="card_id" id="card" class="chosen-js-select form-control">
                                    <option value="">Все карты</option>
                                    @foreach ($cards as $card)<option value="{{$card->id}}" @if (isset($_GET['card_id']) && $card->id == $_GET['card_id']) selected @endif>...{{substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4)}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="max-width: 400px">
                                <label for="status">Статус</label><br>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Все</option>
                                    @foreach ($statuses as $s)
                                    <option value="{{$s}}" @if (isset($_GET['status']) && $_GET['status'] == $s) selected="selected" @endif>{{ucfirst($s)}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="max-width: 400px">
                                <label for="currency">Валюта</label><br>
                                <select name="currency" id="currency" class="form-control">
                                    <option value="">Все</option>
                                    @foreach ($currencies as $c)
                                    <option value="{{$c}}" @if (isset($_GET['currency']) && $_GET['currency'] == $c) selected="selected" @endif>{{$c}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="margin-top: 20px">
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
@endsection


@section('scripts_end')
    <script>
        $(document).ready(function() {

            let user_status = "{{Auth::user()->status}}";
            let columnDefs_json = {};
            if (user_status != 'admin' && user_status != 'accountant') {
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
                "lengthMenu": [ 10, 25, 50, 75, 100, 200, 500 ],
                "columns":[
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
                "drawCallback": function(settings) {
                    $(".token_status").each(function (index) {
                        if ($(this).html() == 'confirmed' ) {
                            $(this).closest('tr').css('background-color', 'rgb(144, 238, 144)');
                        }
                        if ($(this).html() == 'trash' ) {
                            $(this).closest('tr').css('background-color','rgb(250, 128, 114)');
                        }
                    });
                }
            });

            const BEEP = (soundObj) => {
                let sound = document.getElementById(soundObj);
                if (sound)
                    sound.play();
            }

            var tokens_count   = null;
            let checkTokensUrl = "{{url('/api/token_notify')}}?user_id={{Auth::user()->id}}&user_status={{Auth::user()->status}}";

            const checkTokens = () => {
                $.ajax({
                    url: checkTokensUrl,
                    type: 'GET',
                    success: function(result){

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
        });
    </script>
@endsection