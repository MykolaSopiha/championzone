@extends('layouts.app')


@section('content')
<!-- begin header -->
@section('page-name') Команды @endsection
@include('layouts.headers.home')
<!-- end header -->

<!-- begin main -->
<main class="main" role="main">
    <div class="main-inner">
        <h2>Список учасников</h2>
        <div class="table-responsive">
            <table class="table" id="all_users" width="100%">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Логин</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>TerraLeads ID</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->terra_id}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</main>
<!-- end main -->
@endsection