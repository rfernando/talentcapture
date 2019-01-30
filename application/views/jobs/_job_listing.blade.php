<script src="{{ admin_assets_url('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<?php   
    $CI =& get_instance();
    $CI->load->helper('url');
    $uri=$CI->uri->segment(2);
?>

   <div class="box box-success active-jobs" id="active">
    <div class="box-header with-border">
        <h3 class="box-title">Active {{ ($active_jobs->count() > 1) ? 'Jobs' : 'Job' }}</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body {{ ($active_jobsCount = $active_jobs->count()) ? 'no-padding' : ''  }}">
        @if($active_jobsCount)
           <div align="center">
           <select id="job_lst" name="job_lst" onChange="jobView(this.value)" style="width: 95%">
			   <option value="new000">Select A Job</option>
            <?php /*?><ul class="nav nav-pills nav-stacked"><?php */?>
                @foreach($active_jobs as $j)
			   <option value="{{$j->id}}">{{substr($j->title,0,37)}}{{(strlen($j->title)>37)?'...':''}}</option>
                    <?php /*?><li class="{{ (isset($job->id) && $j->id == $job->id) ? 'active' : '' }}">
                        <a href="">  
                        </a>
                    </li><?php */?>
                @endforeach
            <?php /*?></ul><?php */?>
		</select><br><br>
        @if(isset($job->id))
        <script>
		document.getElementById('job_lst').value="{{$job->id}}";
		</script>
        @endif
        </div>

        @else
            <p>You do not have any active Jobs. Please add a new Job.</p>
        @endif

        <?php if(isset($job->id) && ($uri!='job_detail' && $uri!='view_detail')) { ?>  
        <a class="btn btn-back-to-talentgram" href="{{ base_url('jobs/view_detail/'.$job->id) }}" data-toggle="tooltip" title="Return to Job Description">
            <i class="fa fa-arrow-circle-o-left fa-2x"></i>
        </a>
       <?php } ?>

    </div>
	<script>
		function jobView(val)
		{
			if (val=="new000")
				{
					window.location.href="{{ base_url('jobs#active/') }}";
				}
			else
				{
					window.location.href="{{ base_url('jobs/view_detail/"+val+"') }}";
				}
		}
	
	</script>
    <!-- /.box-body -->
</div>
<!-- /. box -->
<div class="box box-danger closed-jobs" id="closed">
    <div class="box-header with-border">
        <h3 class="box-title">Closed Jobs</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body {{ ($closed_jobs->count()) ? 'no-padding' : ''  }}">
        @if($closed_jobs->count())
            <ul class="nav nav-pills nav-stacked">
               <div align="center">
           <select id="job_lst2" name="job_lst2" onChange="jobView2(this.value)" style="width: 95%">
			   <option value="new000">Select A Job</option>
            <?php /*?><ul class="nav nav-pills nav-stacked"><?php */?>
                @foreach($closed_jobs as $j)
			   <option value="{{$j->id}}">{{ substr($j->title,0,38) }}{{ (strlen($j->title) > 38) ? '...' : ''  }}</option>
                    <?php /*?><li class="{{ (isset($job->id) && $j->id == $job->id) ? 'active' : '' }}">
                        <a href="">  
                        </a>
                    </li><?php */?>
                @endforeach
            <?php /*?></ul><?php */?>
		</select><br><br>
        @if(isset($job->id))
        <script>
		document.getElementById('job_lst2').value="{{$job->id}}";
		</script>
        @endif
        </div>
               <?php /*?> @foreach($closed_jobs as $j)
                    <li class="{{ (isset($job->id) && $j->id == $job->id) ? 'active' : '' }}">
                        <a href="{{ base_url('jobs/view_detail/'.$j->id) }}">
                            {{ substr($j->title,0,50) }}{{ (strlen($j->title) > 50) ? '...' : ''  }}
                        </a>
                    </li>
                @endforeach<?php */?>
            </ul>
        @else
            <p>You do not have any Closed Jobs.</p>
        @endif

       <?php if(isset($job->id) && ($uri!='job_detail' && $uri!='view_detail')) { ?> 
            <a class="btn btn-back-to-talentgram" href="{{ base_url('jobs/view_detail/'.$job->id) }}" data-toggle="tooltip" title="Return to Job Description">
                <i class="fa fa-arrow-circle-o-left fa-2x"></i>
            </a>
        <?php } ?>
        
    </div>
    <script>
		function jobView2(val)
		{
			if (val=="new000")
				{
					window.location.href="{{ base_url('jobs#closed/') }}";
				}
			else
				{
					window.location.href="{{ base_url('jobs/view_detail/"+val+"') }}";
				}
		}
	
	</script>
    <!-- /.box-body -->
</div>
<!-- /.box -->

@section('page-js')
    @parent
    <script src="{{ admin_assets_url('plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
@endsection

@section('end-script')

    @parent
    <script>
        $('ul.job-listing').slimScroll({
            color: '#3c8dbc',
            height: 'auto'
        });
    </script>

    <script>
        var hash = window.location.hash;
        if(hash == '#active'){
            $('#closed').hide();
            $(hash).show();
        }else if(hash == '#closed'){
            $('#active').hide();
            $(hash).show();
        }

        $('li.job-list').click(function(){
            hash = $(this).attr('data-job-status');
            if(hash == '#active'){
                $('#closed').hide();
                $(hash).show();
                $('li#active').addClass('active');
                $('#closed').removeClass('active');
            }else if(hash == '#closed'){
                $('#active').hide();
                $(hash).show();
                $('li#closed').addClass('active');
                $('#active').removeClass('active');
            }



        });
    </script>

@endsection