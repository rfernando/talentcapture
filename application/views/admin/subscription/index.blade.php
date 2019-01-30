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
            User Plans
            <small> List of users and their subscription plans</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-envelope"></i>Subscription</li>
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
                                    <th>User Name</th>
                                    <th>Plans</th>
                                    <th>Nos of Days</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=0;?>
                                @foreach ($subscription as $subscriptiondetail)
                                    <?php $i++; ?>
                                    <tr>
                                    
                                        <td>{{ $i }}</td>
                                        <td>{{ $subscriptiondetail->user->first_name }} {{ $subscriptiondetail->user->last_name }}</td>
                                        <td>{{ $subscriptiondetail->plan_name }}</td>
                                        <td>        
                                            {{ $subscriptiondetail->no_of_days}}
                                        </td>
                                        <td>        
                                            <?php echo date('d-m-Y',strtotime($subscriptiondetail->created_at)); ?>
                                        </td>
                                        <td>        
                                            <?php echo date('d-m-Y', strtotime($subscriptiondetail->created_at. ' + '.$subscriptiondetail->no_of_days.' days')); ?>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>User Name</th>
                                    <th>Plans</th>
                                    <th>Nos of Days</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                                </tfoot>
                                
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
