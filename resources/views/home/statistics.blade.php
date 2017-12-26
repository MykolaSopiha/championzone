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
                            <label for="user">Пользователь</label>
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
                            <label for="card">Карта</label>
                            <select name="card" id="card" class="chosen-js-select">
                                <option value="">Выберите карту</option>
                                @foreach ($cards as $card)
                                    @if (isset($_REQUEST['card']) && $_REQUEST['card'] == $card->id)
                                        <option value="{{ $card->id }}" title="{{ $card->currency }}" selected>...{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} ({{ $card->currency }}) {{ $card->name }}</option>
                                    @else
                                        <option value="{{ $card->id }}" title="{{ $card->currency }}">...{{ substr(decrypt($card->code), -8, -4)." ".substr(decrypt($card->code), -4) }} ({{ $card->currency }}) {{ $card->name }}</option>
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
                                    <th>Баланс, USD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statistics as $s)
                                <tr>
                                    <td>{{ $s['day']  }}</td>
                                    <td>{{ $s['cost'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Всего:</td>
                                    <td>{{ $total }} USD</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </main>
    <!-- end main -->

@endsection


@section('scripts_end')
    <script>
        $(document).ready(function() {
            $('#statistics_list').DataTable({});
        });
    </script>
@endsection