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
            Message Templates
            <small> List of Site Messages Templates added by Admin</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-envelope"></i> Message Templates</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                       
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {{ flash_msg() }}
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Type</th>
                                    <th>Message</th>
                                   
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=0;?>
                                @foreach ($message_templates as $message_template)
                                    <?php $i++; ?>
                                    <tr>
                                    
                                        <td>{{ $i }}</td>
                                        <td>{{ $message_template->type }}</td>
                                        <td>{{ $message_template->msg }}</td>
                                        <td>        
                                            <a href="{{ admin_url('message/edit/'.$message_template->id) }}" class="btn btn-warning"
                                               data-toggle="tooltip" title="Update Job Detail"><i class="fa fa-pencil"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                
                            </table>
                        
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
                    {"targets": [0,3], "orderable": false, "searchable": false}
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
