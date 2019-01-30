<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">{{ ($candidates->count() > 1) ? 'Candidates' : 'Candidates' }}</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body {{ ($candidatesCount = $candidates->count()) ? 'no-padding' : '' }}">
        @if($candidates->count())
        <?php
			$hired_tab="active";
			$accepted_tab="";
			$pending_tab="";
			$rejected_tab="";
		?>
			@foreach($candidates as $c)
				@if($c->hired)
					@if(isset($candidate->id) && $candidate->id==$c->id)
						<?php
							$hired_tab="active";
							$accepted_tab="";
							$pending_tab="";
							$rejected_tab="";
						?>
					@endif
				@elseif($job->closed)
				@elseif($c->client_accepted)
					@if($c->client_accepted == 1)
						@if(isset($candidate->id) && $candidate->id==$c->id)
							<?php
								$hired_tab="";
								$accepted_tab="active";
								$pending_tab="";
								$rejected_tab="";
							?>
						@endif
					@elseif($c->client_accepted == -1)	
						@if(isset($candidate->id) && $candidate->id==$c->id)
							<?php
								$hired_tab="";
								$accepted_tab="";
								$pending_tab="";
								$rejected_tab="active";
							?>
						@endif
					@endif
				@else
					@if(isset($candidate->id) && $candidate->id==$c->id)
						<?php
							$hired_tab="";
							$accepted_tab="";
							$pending_tab="active";
							$rejected_tab="";
						?>
					@endif
				@endif
			@endforeach
            <div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="{{$pending_tab}}"><a id="pending-tab_link" href="#pending-tab" data-toggle="tab"><i class="fa fa-exclamation-circle text-warning fa-2x" data-toggle="tooltip" title="Approval Pending"></i></a></li>
					<li class="{{$accepted_tab}}"><a id="accepted-tab_link" href="#accepted-tab" data-toggle="tab"><i class="fa fa-thumbs-up text-success fa-2x" data-toggle="tooltip" title="Accepted"></i></a></li>
					<li class="{{$hired_tab}}"><a id="hired-tab_link" href="#hired-tab" data-toggle="tab"><i class="fa fa-handshake-o text-success fa-2x" data-toggle="tooltip" title="Hired"></i></a></li>
					<li class="{{$rejected_tab}}"><a id="rejected-tab_link" href="#rejected-tab" data-toggle="tab"><i class="fa fa-thumbs-down text-danger fa-2x" data-toggle="tooltip" title="Rejected"></i></a></li>
				</ul>
				<div class="tab-content">
					<div class="{{$accepted_tab}} tab-pane" id="accepted-tab">
					<ul class="nav nav-pills nav-stacked">
					<?php $i=0; ?>
						@foreach($candidates as $c)
							@if($c->hired)
							@elseif($job->closed)
							@elseif($c->client_accepted)
								@if($c->client_accepted == 1)
									<li {{ (isset($candidate->id) && $candidate->id==$c->id) ? 'class="active"': '' }}>
										<a style="font-size: 16px" id="accepted-tab_<?php echo $i; ?>" href="{{ base_url('jobs/candidate_detail/'.$job->id.'/'.$c->id) }}" >
											<!--<i class="fa fa-user"></i>--> {{ $c->name }}
										</a>
									</li>
									<?php $i++; ?>
								@endif
							@endif
						@endforeach	
					</ul>
					</div>
					<div class="{{$pending_tab}} tab-pane" id="pending-tab">
					<ul class="nav nav-pills nav-stacked">
					<?php $i=0; ?>
						@foreach($candidates as $c)
							@if($c->hired)
							@elseif($job->closed)
							@elseif($c->client_accepted)
							@else
								<li {{ (isset($candidate->id) && $candidate->id==$c->id) ? 'class="active"': '' }}>
									<a style="font-size: 16px" id="pending-tab_<?php echo $i; ?>" href="{{ base_url('jobs/candidate_detail/'.$job->id.'/'.$c->id) }}" >
										<!--<i class="fa fa-user"></i>--> {{ $c->name }}
									</a>
								</li>
								<?php $i++; ?>
							@endif
						@endforeach		
					</ul>
					</div>
					<div class="{{$hired_tab}} tab-pane" id="hired-tab">
					<ul class="nav nav-pills nav-stacked">
					<?php $i=0; ?>
						@foreach($candidates as $c)
						   @if($c->hired)
								<li {{ (isset($candidate->id) && $candidate->id==$c->id) ? 'class="active"': '' }}>
									<a style="font-size: 16px" id="hired-tab_<?php echo $i; ?>" href="{{ base_url('jobs/candidate_detail/'.$job->id.'/'.$c->id) }}" >
										<!--<i class="fa fa-user"></i>--> {{ $c->name }}
									</a>
								</li>
								<?php $i++; ?>
							@endif
						@endforeach
					</ul>
					</div>
					<div class="{{$rejected_tab}} tab-pane" id="rejected-tab">
					<ul class="nav nav-pills nav-stacked">
					<?php $i=0; ?>
						@foreach($candidates as $c)
							@if($c->hired)
							@elseif($job->closed)
							@elseif($c->client_accepted)
								@if($c->client_accepted == -1)
									<li {{ (isset($candidate->id) && $candidate->id==$c->id) ? 'class="active"': '' }}>
										<a style="font-size: 16px" id="rejected-tab_<?php echo $i; ?>" href="{{ base_url('jobs/candidate_detail/'.$job->id.'/'.$c->id) }}" >
											<!--<i class="fa fa-user"></i>--> {{ $c->name }}
										</a>
									</li>
									<?php $i++; ?>
								@endif
							@endif
						@endforeach		
					</ul>
					</div>
				</div>
			</div>
               
               
               
               
               
               
               <?php /*
                @foreach($candidates as $c)
                    <li {{ (isset($candidate->id) && $candidate->id==$c->id) ? 'class="active"': '' }}>
                        <a href="{{ base_url('jobs/candidate_detail/'.$job->id.'/'.$c->id) }}" >
                            <i class="fa fa-user"></i> {{ $c->name }}
                            ?>@if($c->hired)
                                <i class="fa fa-check-circle pull-right text-success" data-toggle="tooltip" title="Candidate Hired"></i>
                            @elseif($job->closed)
                                <i class="fa fa-circle-o pull-right" style="color: #dd4b39" data-toggle="tooltip" title="Candidate Not Selected"></i>
                            @elseif($c->client_accepted)
                                <i class="fa fa-{{ ($c->client_accepted == 1) ? 'check text-success' : 'times-circle text-danger' }} pull-right" data-toggle="tooltip" title="Candidate {{ ($c->client_accepted == 1) ? 'Accepted' : 'Rejected' }}"></i>
                            @else
                                <i class="fa fa-exclamation-circle pull-right text-warning" data-toggle="tooltip" title="Pending Approval"></i>
                            @endif
                        </a>
                    </li>
                @endforeach
                <?php */?>
                
                
                
                
            </ul>
        @else
            <p class="text-info">No candidates have been submitted to this job.</p>
        @endif
    </div>
</div>
<script>
$('#accepted-tab_link').on('click', function() 
	{
		var element =  document.getElementById('accepted-tab_0');
		if (typeof(element) != 'undefined' && element != null)
		{
			document.getElementById('accepted-tab_0').click();
		}
		else
		{
			document.getElementById('candi_box').innerHTML='<br><br><br><br><br><center>There are no candidates to display.</center><br><br><br><br><br>';
		}
	}
);
	
$('#pending-tab_link').on('click', function() 
	{
		var element =  document.getElementById('pending-tab_0');
		if (typeof(element) != 'undefined' && element != null)
		{
			document.getElementById('pending-tab_0').click();
		}
		else
		{
			document.getElementById('candi_box').innerHTML='<br><br><br><br><br><center>There are no candidates to display.</center><br><br><br><br><br>';
		}
	}
);
	
$('#hired-tab_link').on('click', function() 
	{
		var element =  document.getElementById('hired-tab_0');
		if (typeof(element) != 'undefined' && element != null)
		{
			document.getElementById('hired-tab_0').click();
		}
		else
		{
			document.getElementById('candi_box').innerHTML='<br><br><br><br><br><center>There are no candidates to display.</center><br><br><br><br><br>';
		}
	}
);
	
$('#rejected-tab_link').on('click', function() 
	{
		var element =  document.getElementById('rejected-tab_0');
		if (typeof(element) != 'undefined' && element != null)
		{
			document.getElementById('rejected-tab_0').click();
		}
		else
		{
			document.getElementById('candi_box').innerHTML='<br><br><br><br><br><center>There are no candidates to display.</center><br><br><br><br><br>';
		}
	}
);
</script>
<!-- /. box -->