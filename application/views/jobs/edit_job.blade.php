@extends('template.template')


@section('page-css')
        <!-- Select2 -->
<link rel="stylesheet" href="{{ admin_assets_url('plugins/select2/select2.min.css') }}">
@endsection


@section('main-content')
    <section class="content-header">
        <h1>
            Jobs
            <small>Add a new Job</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ base_url('jobs') }}"><i class="fa fa-briefcase"></i> Jobs</a></li>
            <li class="active"><i class="fa fa-pencil"></i> Update</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                @include('jobs._job_listing')
            </div>
            <!-- /.col -->

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>

                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- /.mail-box-messages -->
                        <form action="{{ base_url('jobs/update_job/'.$job->id) }}" method="post" class="form-horizontal validateForm">
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
                <!-- /. box -->
            </div>
            <!-- /.col -->
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
            placeholder : 'Select Category',
            minimumResultsForSearch: 10
        });

        $(function () {
            CKEDITOR.replace('description');
            CKEDITOR.replace('question');
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

        $('#preferred_agencies').select2({
            placeholder: "Select Preferred Agencies",
            maximumSelectionLength: 5,
            width:'100%'
        });

        //Disable Preferred Agencies select based on notify agencies radio option Click
        $("input[name='notify_agencies']").click(function(){
            if($(this).val() == 1){
                $("#preferred_agencies").prop("disabled", false);
            }else{
                $("#preferred_agencies").prop("disabled", true);
                $("#preferred_agencies").select2("val", "");
            }
        });

        //Disable Preferred Agencies select based on notify agencies radio option value
        $('#industry_id').attr("disabled", true);
        $("#preferred_agencies").prop("disabled", true);
        $("input[name='notify_agencies']").prop("disabled", true);

        /*if($("input[name='notify_agencies']:checked").val() == 1){
            $("#preferred_agencies").prop("disabled", false);
        }else{
            $("#preferred_agencies").select2("val", "");
        }*/

        //$("#description").wysihtml5();

        $('#industry_id').on('change',function () {
            var industryId = $(this).val();
            $("#preferred_agencies").select2("val", "");
            $('#preferred_agencies').select2({
                ajax: {
                    url: '{{ base_url('jobs/get_agencies')}}' +'/'+ industryId,
                    type: 'GET',
                    dataType : 'json',
                    processResults: function (data) {
                        console.log(data);
                        return {results: data};
                    }
                },
                maximumSelectionLength: 5,
                placeholder: "Select Preferred Agencies",
                minimumResultsForSearch: 10,
                width:'100%'
            });

            $('#profession_id').select2({
                ajax: {
                    url : '{{ base_url('jobs/get_profession')}}' +'/'+ industryId,
                    method : 'post',
                    dataType : 'json',
                    processResults: function (data) {
                        console.log(data);
                        return {results: data};
                    }
                },
                minimumResultsForSearch: 10,
                width:'100%'
            });
        });
    </script>
@endsection