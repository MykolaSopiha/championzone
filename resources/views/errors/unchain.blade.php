@extends('layouts.app')


<!-- begin header -->
@section('page-name') Карты @endsection
@include('layouts.headers.home')
<!-- end header -->


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 text-center">
                <h3><br>Невозможно отвязать карту.</h3>
                <p>По даной карте есть активные токены. Пользователь может отвязать карту только когда все токены по данной карте обработаны.</p>
                <p><a href="{{url('/home/tokens')}}?card_id={{$id}}">посмотреть список</a></p>
            </div>
        </div>
    </div>
@endsection