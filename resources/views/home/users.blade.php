@extends('layouts.app')


@section('content')

    <!-- begin header -->
    @section('page-name') Пользователи @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <div class="table-responsive">
                <table class="table" id="all_users" width="100%">
                    <thead>
                        <tr>
                            <td>id</td>
                            <td>Login</td>
                            <td>First name</td>
                            <td>Last name</td>
                            <td>TerraLeads ID</td>
                            <td>Status</td>
                            <td>Account created</td>
                            <td>Edit</td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </main>
    <!-- end main -->
@endsection


@section('scripts_end')
    <script>
        $(document).ready(function() {

            $('#all_users').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{url('/api/users')}}",
                "responsive": true,
                "columns":[
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'first_name'},
                    {data: 'last_name'},
                    {data: 'terra_id'},
                    {data: 'status'},
                    {data: 'created_at'},
                    {data: 'edit', searchable: false}
                ]
            });

        });
    </script>
@endsection