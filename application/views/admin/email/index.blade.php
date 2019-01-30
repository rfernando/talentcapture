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
            Email Templates
            <small> List of Email Templates added by Admin</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-envelope"></i> Email Templates</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">List of Email Templates</h3><a class="label label-success" href="{{admin_url('email/variable_guide')}}" style="margin-left: 10px;" title="" data-original-title="Variable Guide" target="_blank">Variable Guide</a>
                        <!-- <div class="box-tools pull-right">
                            <a href="{{ admin_url('email/create/') }}" class="btn btn-info" data-toggle="tooltip" title="Add new Subscription Plan"><i class="fa fa-plus"></i></a>
                            <span data-original-title="{{ get_awaiting_approval('email_templates') }} New Subscription Plans" data-toggle="tooltip"
                                  title="" class="badge bg-light-blue">{{ get_awaiting_approval('email_templates') }} New Email Templates </span>
                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" id="delete"
                                    title="Delete Selected"><i class="fa fa-trash"></i></button>
                        </div> -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {{ flash_msg() }}
                        <form action="{{ admin_url('jobs/delete') }}" class="table-form" method="POST">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No.</th>
                                    <th>Email Name</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=0;?>
                                @foreach ($email_templates as $email_template)
                                    <?php $i++; ?>
                                    <tr>
                                        <td class="center"><input type="checkbox" name="selectedRows[]"
                                                                  value="{{ $email_template->id }}"></td>
                                        <td>{{ $i }}</td>
                                        <td>{{ $email_template->template_name }}</td>
                                        <td>{{ $email_template->template_subject }}</td>

                                        <td>
                                            <a class="label ajax {{($email_template->status)  ? 'label-success' : 'label-default'}}"
                                               href="{{admin_url('email/change_status/'.$email_template->id)}}"
                                               data-success="change_status" data-toggle="tooltip"
                                               title="Click to Change">
                                                {{ ($email_template->status) ? "Active" : "Inactive"}}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ admin_url('email/view/'.$email_template->id) }}" class="btn btn-info"
                                               data-toggle="tooltip" title="View Detail"><i
                                                        class="fa fa-info-circle"></i></a>
                                            <a href="{{ admin_url('email/edit/'.$email_template->id) }}" class="btn btn-warning"
                                               data-toggle="tooltip" title="Update Job Detail"><i class="fa fa-pencil"></i></a>
                                            @if(!$email_template->trashed())
                                                <a href="{{ admin_url('email/delete/'.$email_template->id) }}" class="btn btn-danger"
                                                   data-toggle="tooltip" title="Delete this Subscription Plan"
                                                   onclick="return confirm('Do you really want to delete this Subscription Plan ?')"><i
                                                            class="fa fa-trash"></i></a>
                                            @else
                                                <a href="{{ admin_url('email/restore/'.$email_template->id) }}" class="btn btn-success"
                                                   data-toggle="tooltip" title="Restore this Subscription Plan"
                                                   onclick="return confirm('Do you really want to Restore this Subscription Plan ?')"><i
                                                            class="fa fa-recycle"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>No.</th>
                                    <th>Email Name</th>
                                    <th>Subject</th>
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
                    {"width": "1%", "targets": 0},
                    {"targets": [0, 5], "orderable": false, "searchable": false}
                ]
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection
