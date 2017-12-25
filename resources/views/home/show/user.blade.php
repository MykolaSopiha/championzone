@extends('layouts.app')

@section('content')

    <!-- begin header -->
    @section('page-name') Пользователи @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <table class="table">
                <thead>
                    <tr>
                        <td>id</td>
                        <td>Login</td>
                        <td>First name</td>
                        <td>Last name</td>
                        <td>TerraLeads ID</td>
                        <td>Status</td>
                        <td>Account created</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user) 
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->terra_id }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </main>
    <!-- end main -->

@endsection