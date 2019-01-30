@extends('admin.template._main')

@section('title','Update Job Details')@endsection



@section('page-css')
        <!-- Select2 -->
<link rel="stylesheet" href="{{ admin_assets_url('plugins/select2/select2.min.css') }}">
@endsection



@section('main-content')
    <section class="content-header">
        <h1>
           Update Job Details
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ admin_url('jobs') }}"><i class="fa fa-briefcase"></i> Jobs</a></li>
            <li><a href="{{ admin_url('jobs/view/'.$job->id) }}">{{ $job->title }}</a></li>
            <li class="active"><i class="fa fa-pencil"></i> Update</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <!-- /.box-body -->
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Update Job Details</h3>
                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <a class="label ajax {{($job->status)  ? 'label-success' : 'label-default'}}" href="{{admin_url('jobs/change_status/'.$job->id)}}" data-success="change_status" data-toggle="tooltip" title="Click to Change">
                                    {{ ($job->status) ? "Active" : "Inactive"}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="{{ admin_url('jobs/save/'.$job->id) }}" method="post" class="form-horizontal validateForm">
                            {{ flash_msg() }}
                            {{ generate_form_fields($jobDetailsFields, 3) }}
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box -->
            </div>

        </div>
        <!-- /.row -->
    </section>
@endsection


@section('page-js')
    <!-- Select2 -->
    <script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>
    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
@endsection

@section('end-script')
    <script type="text/javascript">
        $('.select2').select2({
            width: '100%',
            placeholder : 'Select Category'
        });

        $('#industry_id').on('change',function () {
            $.ajax({
                url : '{{ admin_url('jobs/get_profession')}}' +'/'+ $(this).val(),
                method : 'post',
                dataType : 'json',
                success : function (result) {
                    $('#profession_id').select2({data : result, width:'100%'})
                }
            });
        });

        $(function () {
            CKEDITOR.replace('description');
        });

        CKEDITOR.on('instanceReady', function () {
            $.each(CKEDITOR.instances, function (instance) {
                CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
                CKEDITOR.instances[instance].document.on("paste", CK_jQ);
                CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
                CKEDITOR.instances[instance].document.on("blur", CK_jQ);
                CKEDITOR.instances[instance].document.on("change", CK_jQ);
            });
        });

        function CK_jQ() {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
    </script>


@endsection