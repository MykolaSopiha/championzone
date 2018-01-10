@extends('layouts.app')


@section('styles')
    <style>
        .chosen-container {
            font-size: 18px !important
        }
        .chosen-single {
            height: 70px !important;
            line-height: 70px !important;
            background: none !important;
            text-align: center
        }
    </style>
@endsection


@section('content')

    <!-- begin header -->
    @section('page-name') Статистика @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">
            
            <!-- begin items -->
            <div class="items">

                <!-- begin items__add -->
                <div class="items__add">

                    <form class="form" method="GET" action="{{ url('/home/statistics') }}">
  
                        {{ csrf_field() }}
  
                        <header class="form__header">
                            <h2>Фильтр расходов</h2>
                        </header>

                        @if (Auth::user()->status === 'admin' || Auth::user()->status === 'accountant')
                        <div class="form__item{{ $errors->has('user') ? ' form__item--error' : '' }}">
                            <label for="user">Пользователь</label><br>
                            <select name="user" id="user" class="chosen-js-select">
                                <option value="">Вce пользователи</option>
                                @foreach ($users as $user)
                                    @if (isset($_REQUEST['user']) && $_REQUEST['user'] == $user->id)
                                        @if ($user->first_name == "" || $user->last_name == "")
                                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                        @else
                                            <option value="{{ $user->id }}" selected>{{ $user->first_name." ".$user->last_name }}</option>
                                        @endif
                                    @else
                                        @if ($user->first_name == "" || $user->last_name == "")
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @else
                                            <option value="{{ $user->id }}">{{ $user->first_name." ".$user->last_name }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('date'))
                                <p>{{ $errors->first('user') }}</p>
                            @endif
                        </div>
                        @endif


                        <div class="form__item{{ $errors->has('card') ? ' form__item--error' : '' }}">
                            <label for="card">Карта</label><br>
                            <select name="card" id="card" class="chosen-js-select">
                                <option value="">Выберите карту</option>
                                @foreach ($cards as $card)
                                    @if (isset($_REQUEST['card']) && $_REQUEST['card'] == $card->id)
                                        <option value="{{ $card->id }}" title="{{ $card->currency }}" data-card-owner="{{ $card->user_id }}" selected>...{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} ({{ $card->currency }}) {{ $card->name }}</option>
                                    @else
                                        <option value="{{ $card->id }}" title="{{ $card->currency }}" data-card-owner="{{ $card->user_id }}">...{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} ({{ $card->currency }}) {{ $card->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('card'))
                                <p>{{ $errors->first('card') }}</p>
                            @endif
                        </div>
  
                        <label>За период</label>
                        <div class="form__two-columns">
                            <div class="form__item">
                                <input id="from" class="pick_date" type="text" name="from" value="{{isset($_REQUEST['from']) ? ($_REQUEST['from']) : ''}}" placeholder="с">
                                @if ($errors->has('first_name'))
                                    <p>{{ $errors->first('first_name') }}</p>
                                @endif
                            </div>
      
                            <div class="form__item">
                                <input id="to" class="pick_date" type="text" name="to" value="{{isset($_REQUEST['to']) ? ($_REQUEST['to']) : ''}}" placeholder="по">
                                @if ($errors->has('last_name'))
                                    <p>{{ $errors->first('last_name') }}</p>
                                @endif
                            </div>
                        </div>
  
                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i> Показать
                            </button>
                        </div>
  
                    </form>
  
                </div>

                <div class="items__list">
                    <h2>Статистика по дням</h2>

                    <div class="table-responsive">
                        <table class="table" id="statistics_list">
                            <thead>
                                <tr>
                                    <th>День</th>
                                    <th>Общий баланс, USD*</th>
                                    <th>Рублевые токены, RUB*</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statistics as $s)
                                <tr>
                                    <td>
                                        <a href="{{url('home/tokens/')}}?date={{$s['day']}}{{$get_req}}">
                                            {{$s['day']}}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{url('home/tokens/')}}?date={{$s['day']}}&status=confirmed{{$get_req}}">
                                            {{ number_format($s['cost'], 2, ".", " ") }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{url('home/tokens/')}}?date={{$s['day']}}&currency=RUB&status=confirmed{{$get_req}}">
                                            {{ number_format($s['cost_RUB'], 2, ".", " ") }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Всего:</td>
                                    <td>{{ number_format($total, 2, ".", " ") }} USD</td>
                                    <td>{{ number_format($total_RUB, 2, ".", " ") }} RUB</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <p><strong>* +10%</strong> - средняя комиссия за транзакцию</p>

                </div>
            </div>

<!--             <div class="table-responsive" style="margin-top: 30px;">
                <table class="table" id="tokens_table">
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
            </div> -->

            <!-- Modal -->
<!--             <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog"> -->

                    <!-- Modal content-->
<!--                     <div class="modal-content">
                        <div class="modal-header" style="padding:35px 50px;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4>Токены</h4>
                        </div>
                        <div class="modal-body" style="padding:20px">
                            <div class="table-responsive">
                                <table class="table" id="tokens_table">
                                    <thead>
                                        <tr>
                                            <td>Дата</td>
                                            <td>Пользователь</td>
                                            <td>Карта</td>
                                            <td>Сумма</td>
                                            <td>Валюта</td>
                                            <td>Курс</td>
                                            <td>Действие</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary pull-left" data-dismiss="modal">Ок</button>
                            <button type="submit" class="btn btn-default pull-left" data-dismiss="modal">Перейти</button>
                        </div>
                    </div>

                </div>
            </div> -->
            <!-- Modal --> 


        </div>
    </main>
    <!-- end main -->

@endsection


@section('scripts_end')
    <script>
        $(document).ready(function() {

            // $('#statistics_list a').click(function (e) {
            //     e.preventDefault();
            // });

            // $('#myModal').modal('show');

            $('#statistics_list').DataTable({
                "order": [[ 0, "desc" ]]
            });

            // let user_status = "{{Auth::user()->status}}";
            // let columnDefs_json = {};
            // if (user_status != 'admin' && user_status != 'accountant') {
            //     columnDefs_json = {
            //         "targets": [1],
            //         "visible": false,
            //         "searchable": true
            //     };
            // }

            // let $table = $('#tokens_table').DataTable({
            //     "processing": true,
            //     "serverSide": true,
            //     "searching": false,
            //     "ajax": {
            //         url: "{{url('/api/tokens')}}",
            //         data: {
            //             data: window.location.search.toString().substr(1)
            //         }
            //     },
            //     "responsive": true,
            //     "ordering": false,
            //     "lengthMenu": [ 10, 25, 50, 75, 100, 200, 500 ],
            //     "columns":[
            //         {data: 'date'},
            //         {data: 'user_name'},
            //         {data: 'card_code'},
            //         {data: 'value'},
            //         {data: 'currency'},
            //         {data: 'rate'},
            //         {data: 'action'},
            //         {data: 'ask'},
            //         {data: 'ans'},
            //         {data: 'status'},
            //         {data: 'tools'}
            //     ],
            //     "columnDefs": [columnDefs_json],
            //     "initComplete": function () {
            //     },
            //     "drawCallback": function(settings) {
            //         $(".token_status").each(function (index) {
            //             if ($(this).html() == 'confirmed' ) {
            //                 $(this).closest('tr').css('background-color', 'rgb(144, 238, 144)');
            //             }
            //             if ($(this).html() == 'trash' ) {
            //                 $(this).closest('tr').css('background-color','rgb(250, 128, 114)');
            //             }
            //         });
            //     }
            // });

        });
    </script>
@endsection