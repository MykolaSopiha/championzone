@extends('layouts.app')


@section('page-name') Бухгалтерия #{{ $bookkeeping->id }} @endsection


@section('content')

    <!-- begin header -->
    @include('layouts.headers.home')
    <!-- end header -->


    <!-- begin main -->
    <main class="main">
        <div class="main-inner">

            <form class="form" method="POST" action="{{ route('home.bookkeepings.update', $bookkeeping->id) }}">

                {{ csrf_field() }}

                <header class="form__header">
                    <h2>Бухгалтерия #{{$bookkeeping->id}}</h2>
                </header>

                <div class="form__item{{ $errors->has('name') ? ' form__item--error' : '' }}">
                    <label for="name">Название</label>
                    <input id="name" type="text" name="name" value="{{ $bookkeeping->name }}" placeholder="Чемпионская">
                    @if (!$errors->has('name'))
                        <p class="text-warning">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('label') ? ' form__item--error' : '' }}">
                    <label for="label">Название</label>
                    <input id="label" type="text" name="label" value="{{ $bookkeeping->label }}" placeholder="чемп">
                    @if (!$errors->has('label'))
                        <p class="text-warning">{{ $errors->first('label') }}</p>
                    @endif
                </div>

                <div class="form__item{{ $errors->has('description') ? ' form__item--error' : '' }}">
                    <label for="description">Информация</label>
                    <textarea id="description" cols="800" rows="10" name="description"
                              placeholder="Описание аккаунта">{{$bookkeeping->description}}</textarea>
                    @if ($errors->has('description'))
                        <p>{{ $errors->first('description') }}</p>
                    @endif
                </div>

                <div class="form__item">
                    <button type="submit">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                    </button>
                </div>

            </form>

        </div>
    </main>
    <!-- end main -->

@endsection
