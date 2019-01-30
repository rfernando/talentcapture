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
        Subscription Plans
        <small> List of Subscription Plans added by Admin</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><i class="fa fa-briefcase"></i> Subscription Plans</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">List of Subscription Plans</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ admin_url('plans/create/') }}" class="btn btn-info" data-toggle="tooltip" title="Add new Subscription Plan"><i class="fa fa-plus"></i></a>
                        <span data-original-title="{{ get_awaiting_approval('subscription_plans') }} New Subscription Plans" data-toggle="tooltip"
                              title="" class="badge bg-light-blue">{{ get_awaiting_approval('subscription_plans') }} New Subscription Plans</span>
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" id="delete"
                                title="Delete Selected"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    {{ flash_msg() }}
                    <form action="{{ admin_url('jobs/delete') }}" class="table-form" method="POST">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Plane Name</th>
                                <th>No. of Days</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($subscription_plans as $subscription_plan)
                                <tr>
                                    <td class="center"><input type="checkbox" name="selectedRows[]"
                                                              value="{{ $subscription_plan->id }}"></td>
                                    <td>{{ $subscription_plan->plan_name }}</td>
                                    <td>{{ $subscription_plan->no_of_days }}</td>
                                    <td>{{ $subscription_plan->amount }}</td>
                                    <td>
                                        <a class="label ajax {{($subscription_plan->status)  ? 'label-success' : 'label-default'}}"
                                           href="{{admin_url('plans/change_status/'.$subscription_plan->id)}}"
                                           data-success="change_status" data-toggle="tooltip"
                                           title="Click to Change">
                                            {{ ($subscription_plan->status) ? "Active" : "Inactive"}}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ admin_url('plans/view/'.$subscription_plan->id) }}" class="btn btn-info"
                                           data-toggle="tooltip" title="View Detail"><i
                                                    class="fa fa-info-circle"></i></a>
                                        <a href="{{ admin_url('plans/edit/'.$subscription_plan->id) }}" class="btn btn-warning"
                                           data-toggle="tooltip" title="Update Job Detail"><i class="fa fa-pencil"></i></a>
                                        @if(!$subscription_plan->trashed())
                                            <a href="{{ admin_url('plans/delete/'.$subscription_plan->id) }}" class="btn btn-danger"
                                               data-toggle="tooltip" title="Delete this Subscription Plan"
                                               onclick="return confirm('Do you really want to delete this Subscription Plan ?')"><i
                                                        class="fa fa-trash"></i></a>
                                        @else
                                            <a href="{{ admin_url('plans/restore/'.$subscription_plan->id) }}" class="btn btn-success"
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
                                <th></th>
                                <th>Plane Name</th>
                                <th>No. of Days</th>
                                <th>Amount</th>
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
