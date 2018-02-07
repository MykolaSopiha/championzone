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
                            <td>Логин</td>
                            <td>Имя</td>
                            <td>Фамилия</td>
                            <td>TerraLeads ID</td>
                            <td>Баланс, USD</td>
                            <td>Статус</td>
                            <td>Зарегистрирован</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </main>
    <!-- end main -->


    <!-- Modal for user delete -->
    <div class="modal fade" id="deleteUser" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="padding:35px 50px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Вы уверены, что хотите удалить пользователя?</h4>
                </div>
                <div class="modal-body text-center" style="padding:20px">
                    <h4 class="user-name"></h4>
                    <h5 class="user-role"></h5>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger  pull-left" id="deleteBtn">Удалить</a>
                    <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Отмена</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal for user delete -->

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
                    {data: 'balance', searchable: false},
                    {data: 'status'},
                    {data: 'created_at'},
                    {data: 'edit', searchable: false}
                ],
                "columnDefs" : [
                    {"targets": [8], "searchable": false},
                    {"targets": [8], "orderable": false}
                ],
                "drawCallback" : function () {
                    $('.delete-user').click(function (e) {
                        e.preventDefault();
                        let modal = $('#deleteUser');

                        modal.find('.user-name').html($(this).attr('data-name'));
                        modal.find('.user-role').html($(this).attr('data-role'));
                        modal.find('#deleteBtn').attr('href', $(this).attr('href'));

                        modal.modal();
                    });
                }
            });

        });
    </script>
@endsection