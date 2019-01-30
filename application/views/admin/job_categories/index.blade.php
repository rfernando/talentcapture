@extends('admin.template._main')

@section('title','Job Category list')

@section('page-css')
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/select2/select2.min.css') }}">


@endsection

@section('main-content')

    <div class="modal fade" id="edit-category-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Update Category</h4>
                </div>
                <div class="modal-body">
                    <form role="form" action="{{ admin_url('job_categories/edit') }}" id="edit-form" method="POST" onsubmit="validate_form()">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control input-lg" id="edit-title" name="title" onblur="validate_form()" placeholder="Enter Job Category Title" autocomplete="off" type="text">
                            </div>
                            <div class="form-group">
                                <label >Industry</label><br>
                                <select class="select2" name="parent_id" id="edit-parent_id">
                                    <option value="">-- No Parent --</option>
                                    @foreach($job_categories as $opt)
                                        <option value="{{ $opt->id }}">{{ $opt->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary pull-right" >Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Job Categories
            <small>List of Job Categories</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-sitemap"></i> Job Categories</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-8">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-sitemap"></i> List of Job Categories</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Delete Selected"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {{ flash_msg() }}
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Industry</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($job_categories as $category)
                                <tr>
                                    <td class="center"><input type="checkbox" name="selectedRows[]" value="{{$category->id }}"></td>
                                    <td class="title_val">{{ $category->title }}</td>
                                    <td class="parent_id_val" data-parent_id="{{ $category->parent_id }}">{{ ($category->parent()->count()) ? $category->parent()->first()->title : '-'  }}</td>
                                    <td class="center">{{ (!$category->trashed())? "<label class='label label-success'>Active</label>": "<label class='label label-danger'>Deleted</label>" }}</td>
                                    <td>
                                        @if(!$category->trashed())
                                            <button data-url="{{ admin_url('job_categories/edit/'.$category->id)}}" class="btn btn-info edit-category" data-toggle="modal" data-target="#edit-category-modal"><i class="fa fa-pencil"></i></button>
                                            <a href="{{ admin_url('job_categories/delete/'.$category->id)}}" class="btn btn-danger" onclick="return confirm('Do you really want to delete this Job category ?')"><i class="fa fa-trash"></i></a>
                                        @else
                                            <a href="{{ admin_url('job_categories/restore/'.$category->id)}}" class="btn btn-success" onclick="return confirm('Do you really want to Restore this Job category ?')"><i class="fa fa-recycle"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Industry</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-plus-circle"></i>  New Job Category</h3>
                    </div>
                    <form role="form" action="{{ admin_url('job_categories/add') }}" method="POST" onsubmit="validate_form()">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input class="form-control input-lg" id="title" name="title" onblur="validate_form()" placeholder="Enter Job Category Title" autocomplete="off" type="text">
                            </div>
                            <div class="form-group">
                                <label >Industry</label><br>
                                <select class="select2" name="parent_id" id="parent_id">
                                    <option value="">-- No Parent --</option>
                                    @foreach($job_categories as $opt)
                                        <option value="{{ $opt->id }}">{{ $opt->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right" >Submit</button>
                        </div>
                    </form>
                </div>
            </div>

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
<script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>



<script>
    $(function () {

        $(".select2").select2({width : '100%' });
        var $editParentIdField = $("#edit-parent_id").select2({width : '100%' });

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('#example2').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "order": [[ 1, "desc" ]],
            "columnDefs": [
                { "width": "1%", "targets": 0 },
                {"targets": [0,3], "orderable": false, "searchable": false }
            ]
        });
        
        $('.edit-category').on('click',function () {
            $('#edit-form').attr('action', $(this).data('url'));
            $('#edit-title').val($(this).closest('tr').find('td.title_val').text());
            $editParentIdField.val($(this).closest('tr').find('td.parent_id_val').data('parent_id')).trigger("change");
        });


    });

    function change_status(result, element) {
        var addClass = (result) ? 'label-success' : 'label-default';
        var removeClass = (result) ? 'label-default' : 'label-success';
        var text = (result) ? 'Active' : 'Inactive';
        element.removeClass(removeClass).addClass(addClass).text(text);
    }

    function validate_form(){
        var title = $('#title');
        var value = $.trim(title.val());
        if(!value){
            title.closest('.form-group').removeClass('has-success');
            title.closest('.form-group').addClass('has-error');
        }else{
            title.closest('.form-group').removeClass('has-error');
            title.closest('.form-group').addClass('has-success');
        }
        console.log(value);
        return false;
    }

    // Function is used only when add form is submitted via ajax.
    function add_category_to_table(result, element){
        var dt = $('#example2').dataTable().api();
        var tbody = $('#example2 tbody');
        var tr = $.parseHTML('<tr><td class="center"><input type="checkbox" name="selectedRows[]" value="'+result.id+'"></td>'+
                '<td>'+result.title+'</td>'+
                '<td class="center"><label class="label label-success">Active</label></td>'+
                '<td><a href="{{ admin_url('job_categories/delete')}}/'+result.id+'" class="btn btn-danger" onclick="return confirm(\'Do you really want to delete this Job category ?\')"><i class="fa fa-trash"></i></a></td></tr>');
        tbody.append(tr);
        tr = tbody.find('tr').last();
        dt.row.add(tr);
        dt.draw();

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    }
</script>
@endsection
