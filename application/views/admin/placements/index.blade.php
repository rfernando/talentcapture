@extends('admin.template._main')

@section('title','Users list')@endsection

@section('page-css')
        <!-- jvectormap -->
<link rel="stylesheet" href="{{ admin_assets_url('plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">
<link rel="stylesheet" href="{{ admin_assets_url('plugins/datepicker/datepicker3.css') }}">
@endsection







@section('main-content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Placements
        <small>Placement Details of Agencies</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><i class="fa fa-users"></i> Placements</li>
    </ol>
</section>
<script>
function tlnt_load(val)
	{
		if (val=="new000")
			{
				//var job_type_val = document.getElementById('job_type').value;
				window.location.href="{{ admin_url('placements') }}";
			}
		else
			{
				window.location.href="{{ admin_url('placements/load_talentg/"+val+"') }}";
			}
	}
	
function tlntg_dtls_load(val)
	{
		val2 = document.getElementById('agency_usr_lst').value;
		if (val=="new000")
			{
				window.location.href="{{ admin_url('placements/load_talentg/"+val2+"') }}";
			}
		else
			{
				window.location.href="{{ admin_url('placements/load_talentg_dtls/"+val+"/"+val2+"') }}";
			}
	}

function tlntg_dtls_load_after_cancel()
	{
		val = document.getElementById('agency_tlntg_lst').value;
		/*val2 = document.getElementById('agency_usr_lst').value;

		if (val=="new000")
			{
				window.location.href="{{ admin_url('placements/load_talentg/"+val2+"') }}";
			}
		else
			{
				window.location.href="{{ admin_url('placements/load_talentg_dtls/"+val+"/"+val2+"') }}";
			}*/
			return "91";
	}
	
</script>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-5">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Agency</h3> 
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(count($agencies)>0)
						<select id="agency_usr_lst" onChange="tlnt_load(this.value)"  name="agency_usr_lst" style="width: 95%">
							<option value="new000">Select an Agency</option>
							@foreach($agencies as $agency)
								<option value="{{$agency->id}}">{{$agency->agencyName}}</option>
							@endforeach
						</select><br><br>
                        @endif
                        
                        @if(isset($agency_usr))
                        <script>
								document.getElementById('agency_usr_lst').value={{$agency_usr}};
						</script>
                        @endif
                </div>
                @if(isset($agency_tlntg_info))
                <div class="box-header">
                    <h3 class="box-title">TalentGram</h3> 
                </div>
                <div class="box-body">
                	
					<select id="agency_tlntg_lst" onChange="tlntg_dtls_load(this.value)"  name="agency_tlntg_lst" style="width: 95%">
						<option value="new000">Select a TalentGram</option>
						@foreach($agency_tlntg_info as $agency_tlntg_row)
							<option value="{{$agency_tlntg_row->id}}">{{$agency_tlntg_row->title}}</option>
						@endforeach
					</select><br><br>
					
                </div>
                @endif


					@if(isset($agency_tlntg))
					<script>
							document.getElementById('agency_tlntg_lst').value={{ $agency_tlntg }};
					</script>
					@endif
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        @if(isset($agency_tlntg_dtls_info) && count($agency_tlntg_dtls_info)>0)
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Placement Details <a href="<?php echo admin_url('jobs/edit/'.$agency_tlntg_dtls_info[0]->jobId); ?>"><i class="fa fa-briefcase" title="Edit TalentGram" data-toggle="tooltip"></i></a></h3>
                    <div class="box-tools pull-right">
                        <?php /*?><span data-original-title="{{ get_awaiting_approval('job') }} New Jobs" data-toggle="tooltip"
                              title="" class="badge bg-light-blue">{{ get_awaiting_approval('job') }} New Jobs</span>
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" id="delete"
                                title="Delete Selected"><i class="fa fa-trash"></i></button><?php */?>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    
                    
						<?php $i = 0;?>
                       <table width="100%">
									  <tr>
										  <th>Candidate</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
										  <th><center>Action</center></th>
									  </tr>
									  @foreach($agency_tlntg_dtls_info as $agency_tlntg_dtls_row)
									  <tr style="{{($agency_tlntg_dtls_row->hire_cancelled == 1) ? 'color: red;text-decoration: line-through;' : 'color: black'}}">
										  <td valign="top">{{$agency_tlntg_dtls_row->name}}</td>
										  <td valign="top">{{$agency_tlntg_dtls_row->start_date}}</td>
										  <td valign="top">{{date('Y-m-d',strtotime("+".(int)$agency_tlntg_dtls_row->warranty_period." days", strtotime($agency_tlntg_dtls_row->start_date)))}}</td>
										  <td valign="top">{{"$ ".(str_replace(",","",$agency_tlntg_dtls_row->base_salary))}}</td>
										  <td valign="top"><?php
											  if($agency_tlntg_dtls_row->type=="agency")
											  {
												  echo "$ ".((float)(str_replace(",","",$agency_tlntg_dtls_row->base_salary)))*((float)$agency_tlntg_dtls_row->placement_fee/100.00)*((float)$agency_tlntg_dtls_row->split_percentage/100.00);
											  }
											  else
											  {
												  echo "$ ".(((((float)(str_replace(",","",$agency_tlntg_dtls_row->base_salary)))/100.00)*(float)$agency_tlntg_dtls_row->placement_fee)/100.00)*80.00;
											  }
											  ?>										  

											  </td>
										  <th>
										  
										  
										  <?php
											  $job = Job::find($agency_tlntg_dtls_info[0]->jobId);
											  $candidate = Candidate::with('hire_details')->find($agency_tlntg_dtls_row->id);
											   $hasHireDetails = $candidate->hire_details()->where(['type'=>'Request Payment'])->count();
											  ?>
										  
	<div class="modal fade" tabindex="-1" role="dialog" id="hireCandidate<?php echo $i;?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Hire Candidate</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ admin_url('jobs/hire_candidates/'.$agency_tlntg_dtls_info[0]->jobId.'/'.$candidate->id.'/'.$agency_usr) }}" method="post" id="requestPaymentForm">
                        @if(isset($candidate->hire_details) && $candidate->hire_details->count())
                            @foreach($candidate->hire_details as $hire_detail)
                                <h5>Candidate Reported Hired by: {{ $hire_detail->added_by()->first()->first_name.' '.$hire_detail->added_by()->first()->last_name}}</h5>
                                <dl class="pull-right">
                                    <dt><i class="fa fa-dollar"></i> Base Salary</dt>
                                    <dd>{{ $hire_detail->base_salary }}</dd>
                                </dl>
                                <dl>
                                    <dt><i class="fa fa-calendar"></i> Start Date</dt>
                                    <dd>{{ date('M d, Y', strtotime($hire_detail->start_date)) }}</dd>
                                </dl>
                                @if($hire_detail->additional_info != '')
	                                <dl>
	                                    <dt><i class="fa fa-info-circle"></i> Additional Info</dt>
	                                    <dd>{{ $hire_detail->additional_info }}</dd>
	                                </dl>
	                            @endif
                                <hr>
                            @endforeach
                        @endif

                        <ul>
                            @include('jobs._hired_candidates', ['hired_candidate' => $candidate])
                        </ul>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-form="#requestPaymentForm">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    	</div>
    		@if($agency_tlntg_dtls_row->hire_cancelled == 0)

    		<center>
    				<a href="#hireCandidate<?php echo $i;?>" data-toggle="modal" rel="tooltip" title="Modify Hire Details." class=" {{ ($hasHireDetails) ? 'hired-candidate ' : 'hire-candidate' }}"><i class="fa fa-dollar"></i></a>
					
					<?php   
					    $CI =& get_instance();
					    $CI->load->helper('url');
					    $uid=$CI->uri->segment(5);
					?>


					<a href="{{admin_url('placements/cancel_reported_hire/'.$agency_tlntg_dtls_row->hire_id.'/'.$agency_tlntg_dtls_row->id.'/'.$agency_tlntg_dtls_row->jobId.'/'.$uid)}}" data-toggle="tooltip" title="Cancel Reported Hires" onclick="return confirm('Are you sure you want to cancel this reported hire?')"><i class="fa fa-trash" style="color:red"></i></a>
			
			</center>
			@endif
		</th>
									  </tr>
									  <?php $i++; ?>
									  @endforeach 
								  </table>
                    
                        
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        @endif
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
<script src="{{ admin_assets_url('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<script>
	$('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });
</script>
@endsection
