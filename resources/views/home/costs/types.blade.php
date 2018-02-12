@extends('layouts.app')


@section('content')

    <!-- begin header -->
    @section('page-name') Расходы @endsection
    @include('layouts.headers.home')
    <!-- end header -->

    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <!-- begin items -->
            <div class="items">

                <!-- begin items__add -->
                    <div class="items__add">
                        <form class="form" id='add-card' method="POST" action="{{ url('/home/costtypes') }}">

                            {{ csrf_field() }}

                            <header class="form__header">
                                <h2>Добавить статью расходов</h2>
                                <a href="{{url('home/costs')}}">Вернуться к расходам</a>
                            </header>

                            <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                                <label for="name">Название</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="">
                                @if ($errors->has('name'))
                                    <p>{{ $errors->first('name') }}</p>
                                @endif
                            </div>

                            <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                                <label for="description">Описание</label>
                                <textarea name="description" id="" cols="80" rows="10"></textarea>
                                @if ($errors->has('description'))
                                    <p>{{ $errors->first('description') }}</p>
                                @endif
                            </div>

                            <div class="form__item">
                                <button type="submit">
                                    <i class="fa fa-floppy"></i> Добавить
                                </button>
                            </div>

                        </form>
                    </div>
                    <!-- end items__add -->

                <!-- begin items__list -->
                <div class="items__list">

                    <h2>Cтатьи расходов</h2>

                    <form class="js-form" action="{{url('/home/costtypes')}}" method="post">

                        <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">

                        <!-- begin table -->
                        <div class="table-responsive">
                            <table class="table" id="all_cards" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Описание</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($cost_types as $cost_type)
                                    <tr>
                                        <td>{{$cost_type->name}}</td>
                                        <td>{{$cost_type->description}}</td>
                                        <td>
                                            <a href="{{route('home:cost.types.delete', [$cost_type->id])}}">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
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