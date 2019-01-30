@extends('template.pagetemplate')

@section('main-content')
<?php
 $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
?>
<br>

<form action="{{ base_url('news/job_application/'.$job->id.'/'.$shared_user_id) }}" class="form-horizontal" method="POST">

   

    <div class="box box-{{ ($job->closed) ? 'danger' : 'success' }}">
         {{ flash_msg() }}
         
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-info-circle"></i> Job Detail
            </h3>
            <p id="url" class="hidden"><?php echo $actual_link; ?></p>
            <a class="btn btn-primary copylink margin-r-5" onclick="copyUrl('#url')">Copy Link</a> <!-- Copy Link Ticket RP-795 -->
            <?php if ($email_link == '') { ?> <!-- Removing Apply button if user is come from email RP-790 -->
                <a href="{{ base_url('news/job_application/'.$job->id.'/'.$shared_user_id) }}" class="btn btn-success pull-right">Apply</a>
            <?php } ?>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <div class="col-md-12" id="job-details">
                <dl>
                    <dt>Job Title</dt>
                    <dd>{{ $job->title }}</dd>
                    <hr/>
                    <dt>Industry</dt>
                    <dd>{{ $job->industry()->first()->title }}
                    <hr/>
                    <dt>Primary Skills Required</dt>
                    <dd>{{ $job->skills}}</dd>
                    <hr/>
                    <dt>Location</dt>
                    <dd>{{ $job->job_location }}</dd>
                    <hr/>
                    <dt>Resource Type</dt>
                    <dd>{{ $job->position_type }}</dd>
                    <hr/>
                    <dt>Compensation</dt>
                    <dd>{{ $job->salary }}</dd>
                    <hr/>
                    @if(!empty($job->relocate))
                        <dt>Relocate?</dt>
                        <dd>{{ $job->relocate}}</dd>
                    @endif
                    <hr/>
                </dl>
            </div>
            <div class="col-md-12">
                <dl>
                    <dt>Job Description</dt>
                    <dd><?=(count($edit_agency_job_desc)>0) ? $edit_agency_job_desc->job_description: $job->description; ?></dd>
                </dl>   
            </div>
            <?php if ($email_link == '') { ?> <!-- Removing Apply button if user is come from email RP-790 -->
            <div class="form-group">
                <div class="col-sm-10" style="padding-left: 30px">
                    <button type="submit" class="btn btn-success">Apply</button>
                </div>
            </div>
            <?php } ?>
        </div>

        
    </div>

</form>

@endsection
<!-- Copy link JS -->
<script type="text/javascript">
    function copyUrl(element) {
                var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($(element).text()).select();
                    document.execCommand("copy");
                temp.remove();
            }
</script>