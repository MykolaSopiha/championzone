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
                    <form id='add-card' style="min-width: 100%;" method="POST" action="{{ url('/home/multiple') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить несколько карт</h2>
                            <a href="{{url('home/cards')}}">Добавить одну карту</a>
                        </header>

                        <div>

                        </div>

                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-credit-card-alt"></i> Добавить
                            </button>
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