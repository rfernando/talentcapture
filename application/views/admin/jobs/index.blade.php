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
        Jobs
        <small>List of Jobs added by Employers and Agencies</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><i class="fa fa-briefcase"></i> Jobs</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">List of Jobs</h3>
                    <div class="box-tools pull-right">
                        <span data-original-title="{{ get_awaiting_approval('job') }} New Jobs" data-toggle="tooltip"
                              title="" class="badge bg-light-blue">{{ get_awaiting_approval('job') }} New Jobs</span>
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
                                <th>Title</th>
                                <th>Added By</th>
                                <th>Position Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($jobs as $job)
                                <tr>
                                    <td class="center"><input type="checkbox" name="selectedRows[]"
                                                              value="{{ $job->id }}"></td>
                                    <td>{{ $job->title }}</td>
                                    <td>
                                        <a href="{{ admin_url('users/view/'.$job->user->id) }}" target="_blank"
                                           data-toggle="tooltip" title="View Profile">
                                            {{ $job->user->first_name.' '.$job->user->last_name }}
                                        </a>
                                        <br>
                                        <small>( {{ ucfirst($job->user->type)}} )</small>
                                    </td>
                                    <td>{{ $job->position_type }}</td>
                                    <td class="center">
                                        @if($job->trashed())
                                            <label class="label label-danger">Deleted</label>
                                        @else
                                            @if(!$job->closed)
                                                <a class="label ajax {{($job->status)  ? 'label-success' : 'label-default'}}"
                                                   href="{{admin_url('jobs/change_status/'.$job->id)}}"
                                                   data-success="change_status_rsvp" data-toggle="tooltip"
                                                   title="Click to Change">
                                                    {{ ($job->status) ? "Active" : "Inactive"}}
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" class="label label-danger">Closed</a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ admin_url('jobs/view/'.$job->id) }}" class="btn btn-info"
                                           data-toggle="tooltip" title="View Detail"><i
                                                    class="fa fa-info-circle"></i></a>
                                        <a href="{{ admin_url('jobs/edit/'.$job->id) }}" class="btn btn-warning"
                                           data-toggle="tooltip" title="Update Job Detail"><i class="fa fa-pencil"></i></a>
                                        @if(!$job->trashed())
                                            <a href="{{ admin_url('jobs/delete/'.$job->id) }}" class="btn btn-danger"
                                               data-toggle="tooltip" title="Delete this Job"
                                               onclick="return confirm('Do you really want to delete this Job ?')"><i
                                                        class="fa fa-trash"></i></a>
                                        @else
                                            <a href="{{ admin_url('jobs/restore/'.$job->id) }}" class="btn btn-success"
                                               data-toggle="tooltip" title="Restore this Job"
                                               onclick="return confirm('Do you really want to Restore this Job ?')"><i
                                                        class="fa fa-recycle"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Position Type</th>
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

@section('end-script')
    @parent

    <script type="application/javascript">
        function change_status_rsvp(result, element) {
            element.parent().html(result.msg);
            setTimeout(function () {
                window.location.reload();
            }, 4000); //will call the function after 2 secs.
            
        }
    </script>
@endsection