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
            <li class="active"><i class="fa fa-plus-circle"></i> Add New</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content"  id="main-content">
        <div class="row">
            <div class="col-md-3">
                @include('jobs._job_listing')
            </div>
            <!-- /.col -->
            
            <div class="col-md-9">

                @if($subscription['dstatus'] == 'active')

                    @if($subscription['type'] !="")
                    <div class="alert alert-{{ $subscription['type'] }}">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ $subscription['msg'] }}
                    </div>
                    @endif
                
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>

                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- /.mail-box-messages -->
                            <form action="{{ base_url('jobs/save_job') }}" method="post" class="form-horizontal validateForm">
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
                @else
                    <div class="alert alert-{{ $subscription['type'] }}">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ $subscription['msg'] }}
                    </div>
                @endif
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
    <!-- <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script> -->

    <!-- FastClick -->
    <script src="{{ admin_assets_url('plugins/fastclick/fastclick.js') }}"></script>
      <script src="{{ admin_assets_url('plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- CK Editor -->
    <!-- <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>-->
    <script src="{{ admin_assets_url('plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        $('[rel="popover"]').popover({
            trigger: "hover",
            html:true,
            container: '#main-content',
            content: function() {
                return $('#'+$(this).data('content_id')).html();
            }
        });
    </script>

@endsection


@section('end-script')
    <script type="text/javascript">
        $('.select2').select2({
            width: '100%',
            placeholder : "The fee you agreed to with your client",
            minimumResultsForSearch: 10
        });

        if(document.getElementById("split_percentage") == null){
            $('.select2').select2({
                width: '100%',
                placeholder : "The percentage of salary you agree to pay as a placement fee to the agency recruiter",
                minimumResultsForSearch: 10
            });
        }
        else
        {
            $('.select2').select2({
                width: '100%',
                placeholder : "The fee you agreed to with your client",
                minimumResultsForSearch: 10
            });
        }

        $('.select3').select2({
            width: '100%',
            placeholder : "Percentage of the fee you agree to split with another recruiter",
            minimumResultsForSearch: 10
        });


        if(document.getElementById("split_percentage") == null){
            document.getElementById("description").placeholder = "Job Description";
        }
        else
        {
            document.getElementById("description").placeholder = "If you selected to keep your client name confidential, be sure to not include any mention of the client name in the job description.";
       
        }

        document.getElementById("question").placeholder = "If there are any specifics you want the recruiter to include when submitting a candidate for this job, enter them here. They can be in the form of questions or topics.";

		
        var config = {};

        config.extraPlugins='confighelper';

        $(function () {
            CKEDITOR.replace('description',config);
            CKEDITOR.replace('question',config); 
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

        $("#preferred_agencies").prop("disabled", true);
        var industryId = $("#industry_id").val();
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
            placeholder: "Select Preferred Agencies",
            maximumSelectionLength: 5,
            width:'100%'
        });

        $("input[name='notify_agencies']").click(function(){
            if($(this).val() == 1){
                $("#preferred_agencies").prop("disabled", false);
            }else{
                $("#preferred_agencies").prop("disabled", true);
                $("#preferred_agencies").select2("val", "");
            }
        });


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

        document.getElementById("salary").placeholder = "Salary or Hourly Rate";
        
    </script>

    <script>
        $('[rel="popover"]').popover({
            trigger: "hover",
            html:true,
            container: '#main-content',
            content: function() {
                return $('#'+$(this).data('content_id')).html();
            }
        });
    </script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
</script>

@endsection