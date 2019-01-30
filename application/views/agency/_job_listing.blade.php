<script src="{{ admin_assets_url('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<script>
	function jobView(val)
	{
		if (val=="new000")
			{
				//var job_type_val = document.getElementById('job_type').value;
				window.location.href="{{ base_url('searches#active') }}";
			}
		else
			{
				var job_type_val = document.getElementById('job_type').value;
				var job_type_val2 = document.getElementById('job_type_closed').value;
				window.location.href="{{ base_url('searches/job_detail/"+val+"/"+job_type_val+"/"+job_type_val2+"') }}";
			}
	}
    function jobView2(val)
	{
		if (val=="new000")
			{
				window.location.href="{{ base_url('searches#closed/') }}";
			}
		else
			{
				var job_type_val = document.getElementById('job_type').value;
				var job_type_val2 = document.getElementById('job_type_closed').value;
				window.location.href="{{ base_url('searches/job_detail/"+val+"/"+job_type_val+"/"+job_type_val2+"') }}";
			}
	}



   function jobView3(val,val2)
	{
		var job_type_val = document.getElementById('job_type').value;
		$.ajax({
            url: '{{ base_url('searches/job_type_detail').'/' }}' + val+"/"+val2,
            dataType : 'html',
            success : function (result) 
			{
				job_lst.innerHTML=result;
				if(job_type_val!="new000")
				{
					$('#closed').hide();
				}
            }
        });
   }
   function jobView4(val,val2)
	{
		var job_type_val = document.getElementById('job_type_closed').value;
		$.ajax({
            url: '{{ base_url('searches/job_type_detail_closed').'/' }}' + val+"/"+val2,
            dataType : 'html',
            success : function (result) 
			{
				job_lst2.innerHTML=result;
				if(job_type_val!="new000")
				{
					$('#active').hide();
				}
            }
        });
   }
</script>

<?php   
    $CI =& get_instance();
    $CI->load->helper('url');
    $uri=$CI->uri->segment(2);
?>


<div class="box box-success active-jobs" id="active">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-briefcase"></i> Active TalentGrams</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
		<div align="center">
			<select id="job_type" onChange="jobView3(this.value)"  name="job_type" style="width: 95%">
				<?php
				if($this->loggedInUser->is_trial==0)
				{
				?>
					<option value="any">All TalentGrams</option>
					<option value="agn">Agency Hub</option>
				<?php
				}
				?>
				<option value="emp">Employer Hub</option>
			</select><br><br>
			
			@if(isset($jobOwner_type))
				<script>
					document.getElementById('job_type').value="{{$jobOwner_type}}";
				</script>
			@endif	
			
			<select id="job_lst" name="job_lst" onChange="jobView(this.value)" style="width: 95%">
				<option value="new000">Select A TalentGram</option>
			</select><br><br>
		</div>

		<!-- On user profile page under settings, remove on the left hand side “Professions” -->
		<?php if(isset($jobOwner_type) && ($uri!='job_detail' && $uri!='view_detail')) { ?>
        <a class="btn btn-back-to-talentgram" href="{{ base_url('searches/job_detail/'.$job->id.'/'.$jobOwner_type.'/'.$jobOwner_type_closed) }}" data-toggle="tooltip" title="Return to Job Description">
            <i class="fa fa-arrow-circle-o-left fa-2x"></i>
        </a>
       <?php } ?>

    </div>
</div>


<div class="box box-danger closed-jobs" id="closed">
    <div class="box-header with-border">
        <h3 class="box-title">Archived TalentGrams</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
	<div class="box-body">
		<div align="center">
			<select id="job_type_closed" onChange="jobView4(this.value)"  name="job_type_closed" style="width: 95%">
				
				<?php
				if($this->loggedInUser->is_trial==0)
				{
				?>
					<option value="any">All TalentGrams</option>
					<option value="agn">Agency Hub</option>
				<?php
				}
				?>
				<option value="emp">Employer Hub</option>
			</select><br><br>
			@if(isset($jobOwner_type_closed))
				<script>
					document.getElementById('job_type_closed').value="{{$jobOwner_type_closed}}";
				</script>
			@endif	
			
			<select id="job_lst2" name="job_lst2" onChange="jobView2(this.value)" style="width: 95%">
				<option value="new000">Select A TalentGram</option>
			</select><br><br>
			
	
		</div>

		<!-- On user profile page under settings, remove on the left hand side “Professions” -->
		<?php if(isset($jobOwner_type) && ($uri!='job_detail' && $uri!='view_detail')) { ?> 
		<a class="btn btn-back-to-talentgram" href="{{ base_url('searches/job_detail/'.$job->id.'/'.$jobOwner_type.'/'.$jobOwner_type_closed) }}" data-toggle="tooltip" title="Return to Job Description">
            <i class="fa fa-arrow-circle-o-left fa-2x"></i>
        </a>
        <?php } ?>



    </div>
</div>
			@if(isset($jobOwner_type))
				<script>
					var jt = "{{$jobOwner_type}}";
					var jtn = "{{$jobOwner_type_closed}}";
					if (jt!="none")
					{
						jobView3(document.getElementById('job_type').value,{{$My_job_id}});
					}
					else if (jtn!="none")
					{
						jobView4(document.getElementById('job_type_closed').value,{{$My_job_id}});
					}
				</script>
			@endif

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
            jobView3('any');
        }else if(hash == '#closed'){
            $('#active').hide();
            $(hash).show();
            jobView4('any');
        }
        $('li.so-list').click(function(){
            hash = $(this).attr('data-so-status');
            if(hash == '#active'){
                $('#closed').hide();
                $(hash).show();
                $('li#active').addClass('active');
                $('#closed').removeClass('active');
                jobView3('any');
            }else if(hash == '#closed'){
                $('#active').hide();
                $(hash).show();
                $('li#closed').addClass('active');
                $('#active').removeClass('active');
                jobView4('any');
            }
        });
        
		
		$(document).ready(function()
		{

		});
		
    </script>
    
@endsection