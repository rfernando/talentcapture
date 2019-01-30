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
        Reviews
        <small>List of Reviews added by Employers and Agencies</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><i class="fa fa-star"></i> Reviews</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">List of Reviews</h3>
                    <div class="box-tools pull-right">
                        <?php /*?><span data-original-title="{{ get_awaiting_approval('job') }} New Jobs" data-toggle="tooltip"
                              title="" class="badge bg-light-blue">{{ get_awaiting_approval('job') }} New Jobs</span>
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" id="delete"
                                title="Delete Selected"><i class="fa fa-trash"></i></button><?php */?>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    {{ flash_msg() }}
                    <form action="" class="table-form" method="POST">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Agency</th>
                                <th>rating</th>
                                <th>On</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($reviews as $review)
                                <tr>
                                    <td>
                                    
                                    <a href="{{ admin_url('users/view/'.$review->user_id) }}" target="_blank"
                                           data-toggle="tooltip" title="View Profile">
                                    {{ $review->userName }}
                                    </a>
                                    
                                    </td>
                                    <td>
                                        <a href="{{ admin_url('users/view/'.$review->agency_id) }}" target="_blank"
                                           data-toggle="tooltip" title="View Profile">
                                            {{ $review->agencyName }}
                                        </a>
                                    </td>
                                    <td> {{ $review->rating }} </td>
									<td> {{ $review->created_at }}  </td>
                                    <td class="center">
										<a href="{{admin_url('reviews/change_status/'.$review->user_id.'/'.$review->agency_id)}}" class="label {{($review->rat_status==1)  ? 'label-success' : 'label-default'}} ajax" data-success="update_stat" data-toggle="tooltip" title="Click to Change">
											{{ ($review->rat_status==1) ? "Active" : "Inactive"}}
										</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>User</th>
                                <th>Agency</th>
                                <th>rating</th>
                                <th>On</th>
                                <th>Status</th>
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
	function update_stat() {
          window.location.reload();
        }
	
    $(function () {
        $('#example2').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "order": [],
            "columnDefs": [
                {"targets": [4], "searchable": false}
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
