@extends('layouts.app')

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
                <div class="">

                    <form class="form" method="POST" action="{{ url('/home/statistics') }}">
  
                        {{ csrf_field() }}
  
                        <header class="form__header">
                            <h2>За период</h2>
                        </header>
  
                        <div class="form__item">
                            <label for="from">С</label>
                            <input id="from" class="pick_date" type="text" name="from" required>
                            @if ($errors->has('first_name'))
                                <p>{{ $errors->first('first_name') }}</p>
                            @endif
                        </div>
  
                        <div class="form__item">
                            <label for="to">По</label>
                            <input id="to" class="pick_date" type="text" name="to" required>
                            @if ($errors->has('last_name'))
                                <p>{{ $errors->first('last_name') }}</p>
                            @endif
                        </div>
  
                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Показать
                            </button>
                        </div>
  
                    </form>
  
                </div>

                <div class="items__list">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>День</td>
                                <td>Потрачено, USD</td>
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
                                <td>{{ $total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </main>
    <!-- end main -->

@endsection