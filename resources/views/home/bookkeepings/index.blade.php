@extends('layouts.app')


<!-- begin title -->
@section('page-name') Бухгалтерия @endsection
<!-- end title -->


@section('content')

    <!-- begin header -->
    @include('layouts.headers.home')
    <!-- end header -->

    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <!-- begin items -->
            <div class="items">

                <!-- begin items__add -->
                <div class="items__add">
                    <form class="form" method="POST" action="{{ route('home.bookkeepings.store') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить</h2>
                        </header>

                        <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                            <label for="name">Название</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                   placeholder="Чемпионская">
                            @if (!$errors->has('name'))
                                <p class="text-warning">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('description') ? ' form__item--error' : '' }}">
                            <label for="description">Информация</label>
                            <textarea id="description" cols="800" rows="10" name="description"
                                      placeholder="Описание аккаунта">{{old('description')}}</textarea>
                            @if ($errors->has('description'))
                                <p>{{ $errors->first('description') }}</p>
                            @endif
                        </div>

                        <div class="form__item">
                            <button type="submit">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Добавить
                            </button>
                        </div>

                    </form>
                </div>
                <!-- end items__add -->


                <!-- begin items__list -->
                <div class="items__list">
                    <h2>Бухгалтерии</h2>

                    <!-- begin table -->
                    <div class="table-responsive">
                        <table class="table display js-table" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Информация</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($bookkeepings as $bk)
                                <tr>
                                    <td>{{$bk->id}}</td>
                                    <td>{{$bk->name}}</td>
                                    <td>{{$bk->description}}</td>
                                    <td style="text-align: right;">
                                        <a class="btn btn-link" href="{{route('home.bookkeepings.edit', $bk->id)}}">
                                            <i class="fa fa-pencil fa-lg"></i>
                                        </a>
                                        <a class="btn btn-link" href="{{route('home.bookkeepings.delete', $bk->id)}}">
                                            <i class="fa fa-times fa-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- end table -->

                </div>
                <!-- end items__list -->

            </div>
            <!-- end items -->

        </div>

    </main>
    <!-- end main -->
@endsection
