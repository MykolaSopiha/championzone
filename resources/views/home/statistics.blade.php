@extends('layouts.app')


@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
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

                    <form class="form" method="POST" action="{{ url('/home/statistics') }}">
  
                        {{ csrf_field() }}
  
                        <header class="form__header">
                            <h2>Фильтр расходов</h2>
                        </header>

                        <div class="form__item{{ $errors->has('user') ? ' form__item--error' : '' }}">
                            <label for="user">Пользователь</label>
                            <select name="user" id="user">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('date'))
                                <p>{{ $errors->first('user') }}</p>
                            @endif
                        </div>
  
                        <h3>За период</h3>
                        
                        <div class="form__item">
                            <label for="from">С</label>
                            <input id="from" class="pick_date" type="text" name="from">
                            @if ($errors->has('first_name'))
                                <p>{{ $errors->first('first_name') }}</p>
                            @endif
                        </div>
  
                        <div class="form__item">
                            <label for="to">По</label>
                            <input id="to" class="pick_date" type="text" name="to">
                            @if ($errors->has('last_name'))
                                <p>{{ $errors->first('last_name') }}</p>
                            @endif
                        </div>
  
                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i> Показать
                            </button>
                        </div>
  
                    </form>
  
                </div>

                <div class="items__list">
                    <h2>Расходы за день</h2>

                    <div class="table-responsive">
                        <table class="table" id="statistics_list">
                            <thead>
                                <tr>
                                    <th>День</th>
                                    <th>Потрачено, USD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stat as $s)
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