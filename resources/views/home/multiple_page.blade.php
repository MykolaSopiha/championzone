@extends('layouts.app')

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
                <div>
                    <form class="form" id='add-card' style="min-width: 100%;" method="POST" action="{{ url('/home/multiple') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить несколько карт</h2>
                            <a href="{{url('home/cards')}}">Добавить одну карту</a>
                        </header>

                        <div>
                            <p><strong>Формат: ХХХХХХХХХХХХХХХХ ММ/ГГГГ CW2 .... </strong></p>
                            <br>
                            <p>Выберите пользователя для привязки карт:</p>
                            <br>
                            <select name="card_user">
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <p><br>Введите данные:</p>
                            <br>
                            <textarea style="max-width: 100%" name="cards" id="" cols="300" rows="10"></textarea>
                        </div>

                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-credit-card-alt"></i> Добавить
                            </button>
                        </div>

                        <div>
                            <p>Результаты:</p><br><br>
                            @foreach ($errors as $error)

                                @if (empty($error))
                                <p style="color: green">{{ array_search($error, $errors)."  -  added successfully!" }}</p>
                                @else
                                <p style="color: red">{{ "error!".$error['idx']." ".$error[0] }}</p>
                                @endif

                            @endforeach
                        </div>

                    </form>
                </div>
                <!-- end items__add -->

            </div>
            <!-- end items -->

        </div>
    </main>
    <!-- end main -->
@endsection