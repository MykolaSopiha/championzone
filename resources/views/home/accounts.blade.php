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
    @section('page-name') Аккаунты @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <!-- begin items -->
            <div class="items">

                @if (Auth::user()->status === 'admin')
                <!-- begin items__add -->
                <div class="items__add">
                    <form class="form" method="POST" action="{{ url('/home/accounts') }}">

                        {{ csrf_field() }}

                        <header class="form__header">
                            <h2>Добавить аккаунт</h2>
                        </header>

                        <div class="form__item{{ $errors->has('info') ? ' form__item--error' : '' }}">
                            <label for="info">Информация</label>
                            <textarea id="info" cols="80" rows="10" name="info" placeholder="Описание аккаунта">{{old('info')}}</textarea>
                            @if ($errors->has('info'))
                                <p>{{ $errors->first('info') }}</p>
                            @endif
                        </div>

                        <div class="form__item{{ $errors->has('user') ? ' form__item--error' : '' }}">
                            <label for="user">Пользователь</label>
                            <select name="user" id="user" class="chosen-js-select">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('user'))
                                <p>{{ $errors->first('user') }}</p>
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
                @endif

                <!-- begin items__list -->
                <div class="items__list">
                    <h2>Список аккаунтов</h2>
                    
                    <form class="js-form" action="{{url('/home/accounts/')}}" method="post">
                        <input id="token" type="hidden" name="_token" value="{{csrf_token()}}">
                        <!-- begin table -->
                        <div class="table-responsive">
                            <table class="table display js-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Пользователь</th>
                                        <th>Информация</th>
                                        @if (Auth::user()->status === 'admin')
                                        <th></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $a)
                                    <tr>
                                        <td>{{$a->user_name}}</td>
                                        <td>{{$a->info}}</td>
                                        @if (Auth::user()->status === 'admin')
                                        <td>
                                            <a href="{{url('/home/accounts')}}/{{$a->id}}">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a class="remove-btn" href="{{url('/home/accounts/')}}/{{$a->id}}">
                                                <i class='remove fa fa-times fa-lg' title='Удалить' aria-hidden='true'></i>
                                            </a>

                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table -->
                    </form>
                    <!-- </form> -->

                </div>
                <!-- end items__list -->

            </div>
            <!-- end items -->

        </div>
    </main>
    <!-- end main -->
@endsection