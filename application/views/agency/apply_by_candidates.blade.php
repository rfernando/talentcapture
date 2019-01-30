@extends('template.pagetemplate')


@section('main-content')
    <section class="content-header">
        <h1>
            {{ $job->title }}
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{ base_url('news/email_job_appication/'.$job->id.'/'.$shared_user_id) }}" method="post" class="form-horizontal validateForm" enctype="multipart/form-data">
                            <input type="hidden" name="candidateAppDetails[job_id]" value="{{ $job->id }}">
                            {{ flash_msg() }}
                            {{ generate_form_fields($candidateApplyFields, 2) }}
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection


@section('page-js')
    <script src="{{ admin_assets_url('plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
@endsection


@section('end-script')
    @parent
    <script type="text/javascript">

        $('#candidates_apply-linkedin_url').on('focus', function(){
            var value = $(this).val();
            if(value == '' )
                $(this).val('http://');
        }).on('blur',function(){
            var value = $(this).val();
            if(value != '' && value.indexOf('http://') != 0 && value.indexOf('https://') != 0){
                $(this).val('http://'+value);
            }else if(value == 'http://'){
                $(this).val('');
            }

        });

        document.getElementById("resume").style.width = "40%";
        document.getElementById("candidates_apply-name").placeholder = "Required";
        document.getElementById("candidates_apply-email").placeholder = "Required";
        document.getElementById("candidates_apply-phone").placeholder = "";
        document.getElementById("candidates_apply-linkedin_url").placeholder = "Required";
        document.getElementById("candidates_apply-message").placeholder = "";

        function validate_form(){

            var linked_in = $('#candidates_apply-linkedin_url');
            var linked_in_value = $.trim(linked_in.val());

            var resume = $('#resume');
            var resume_value = $.trim(linked_in.val());

            if(linked_in_value){
                linked_in.closest('.form-group').removeClass('has-success');
                linked_in.closest('.form-group').addClass('has-error');
                return true;
            }else
            {
                if(resume_value){
                    linked_in.closest('.form-group').removeClass('has-error');
                    linked_in.closest('.form-group').addClass('has-success');
                    return true;
                }
                else{
                    return false;
                }
            }
        }

        jQuery(function($){
           $("#candidates_apply-phone").mask("(999) 999-9999");
        });


    </script>
@endsection