@extends('admin.template._main')

@section('title','Users list')@endsection

@section('page-css')
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">

@endsection

@section('main-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Users
            <small>List of Employers and Agencies</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-users"></i> Users</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">List of users</h3>
                        <div class="box-tools pull-right">
                            <span data-original-title="{{ get_awaiting_approval('user') }} New Users" data-toggle="tooltip" title="" class="badge bg-light-blue">{{ get_awaiting_approval('user') }} New Users</span>
                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" id="delete" title="Delete Selected"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {{ flash_msg() }}
                        <form action="{{ admin_url('users/delete') }}" class="table-form" method="POST">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="center"><input type="checkbox" name="selectedRows[]" value="{{ $user->id }}"></td>
                                        <td>{{ $user->first_name.' '.$user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a class="label ajax {{ ($user->type == 'employer')  ? 'label-info' : 'label-warning'}}" href="{{admin_url('users/change_type/'.$user->id)}}" data-success="change_type" data-toggle="tooltip" title="Click to Change">
                                                {{ ucfirst($user->type) }}
                                            </a>
                                        </td>
                                        <td class="center">
                                            @if($user->trashed())
                                                <label  class="label label-danger">Deleted</label>
                                            @else
                                                <a class="label ajax {{($user->status)  ? 'label-success' : 'label-default'}}" href="{{admin_url('users/change_status/'.$user->id)}}" data-success="change_status" data-toggle="tooltip" title="Click to Change">
                                                    {{ ($user->status) ? "Active" : "Inactive"}}
                                                </a>
                                            @endif

                                        </td>
                                        <td>
                                            @if(!$user->trashed())
                                                <!-- <a href="{{ admin_url('users/delete/'.$user->id) }}" class="btn btn-danger" data-toggle="tooltip" title="Delete this User" onclick="return confirm('Do you really want to delete this user ?')"><i class="fa fa-trash"></i></a> -->
                                                <a href="{{ admin_url('users/view/'.$user->id) }}" class="btn btn-info" data-toggle="tooltip" title="View Detailed Profile"><i class="fa fa-info-circle"></i></a>
                                            @else
                                                <a href="{{ admin_url('users/restore/'.$user->id) }}" class="btn btn-success" data-toggle="tooltip" title="Restore this User" onclick="return confirm('Do you really want to Restore this user ?')"><i class="fa fa-recycle"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('page-js')
    <!-- DataTables -->
    <script src="{{ admin_assets_url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ admin_assets_url('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ admin_assets_url('plugins/iCheck/icheck.min.js') }}"></script>


    <script>
        $(function () {
            $('#example2').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "order": [],
                "columnDefs": [
                    { "width": "1%", "targets": 0 },
                    {"targets": [0,5], "orderable": false, "searchable": false }
                ]
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });


        function change_type(result, element) {
            var addClass = (result == 'employer') ? 'label-info' : 'label-warning';
            var removeClass = (result == 'employer') ? 'label-warning' : 'label-info';
            var text = result.charAt(0).toUpperCase() + result.slice(1);
            element.removeClass(removeClass).addClass(addClass).text(text);
        }
    </script>
@endsection
