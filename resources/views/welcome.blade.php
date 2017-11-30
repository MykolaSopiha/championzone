@extends('layouts.app')


@section('content')

    <!-- begin main-page -->
    <div class="main-page">

    @include('layouts.headers.mainpage')

        <!-- begin main-logo -->
            <div class="main-logo">
                <svg class="icon icon-motivation_logo"><use xlink:href="img/sprite.svg#motivation_logo"></use></svg>
            </div>
        <!-- end main-logo -->

    </div>
    <!-- end main-page -->
    
@endsection
