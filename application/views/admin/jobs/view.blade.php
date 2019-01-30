@extends('admin.template._main')

@section('title','Job Details')@endsection

@section('page-css')
        <!-- Select2 -->
<link rel="stylesheet" href="{{ admin_assets_url('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ admin_assets_url('plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">
<link rel="stylesheet" href="{{ admin_assets_url('plugins/datepicker/datepicker3.css') }}">
<script src="{{ base_url('public/js/jquery.min.js')}}"></script>
@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Job Details
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ admin_url('jobs') }}"><i class="fa fa-briefcase"></i> Jobs</a></li>
            <li class="active">{{ $job->title}}</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <!-- /.box-body -->
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Job Details</h3>
                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <a class="label ajax {{($job->status)  ? 'label-success' : 'label-default'}}" href="{{admin_url('jobs/change_status/'.$job->id)}}" data-success="change_status" data-toggle="tooltip" title="Click to Change">
                                    {{ ($job->status) ? "Active" : "Inactive"}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <dl id="job-details">

                            <dt>Job Title</dt>
                            <dd>{{ $job->title }}</dd>

                            <dt>Added By</dt>
                            <dd>{{ $job->user->first_name.' '.$job->user->last_name }}</dd>

                            <dt>Industry</dt>
                            <dd>{{ $job->industry()->first()->title;}}

                            <dt>Profession</dt>
                            <dd>{{ $job->profession()->first()->title;}}</dd>

                            <dt>Location</dt>
                            <dd>{{ $job->job_location }}</dd>

                            <dt>Resource Type</dt>
                            <dd>{{ $job->position_type }}</dd>

                            <dt>Openings</dt>
                            <dd>{{ $job->openings }}</dd>
                            <dt>Client Name</dt>
                            <dd>{{ $job->client_name }} {{ ($job->client_name_confidential) ? '(Confidential)' : '' }}</dd>

                            <dt>Salary or Hourly rate</dt>
                            <dd>{{ $job->salary }}</dd>

                            @if($job->user()->first()->type == 'agency')

                                <dt>Warranty Period</dt>
                                <dd>{{ $job->warranty_period }}</dd>

                                <dt>Split Percentage</dt>
                                <dd>{{ $job->split_percentage }}</dd>

                            @endif

                            <dt>Notes</dt>
                            <dd>{{ $job->note }}</dd>
                            <hr>
                        </dl>

                        <dl>
                            <dt>Description <a class="pull-right toggle-display" href="javascript:void(0)">Hide</a></dt>
                            <dd>{{ $job->description }}</dd>
                        </dl>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-4 no-padding">
                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> Candidates</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body {{ count($candidates) ? 'no-padding' : '' }}">
                        @if(count($candidates))
                            <ul class="nav nav-pills nav-stacked">
                                @foreach($candidates as $c)
                                   
                                   <?php  $c = Candidate::with('hire_details')->find($c->id); ?>
                                   
                                    <li {{ (isset($candidate->id) && $candidate->id==$c->id) ? 'class="active"': '' }}>
                                      		<div class="pull-right" style="margin-top:15px;margin-right:15px;">
                                       		@if($c->hired)
                                                <i class="fa fa-check-circle pull-right text-success" data-toggle="tooltip" title="Candidate Hired"></i>
                                            @elseif($job->closed)
											@elseif($c->hire_details()->where('hire_cancelled','!=','1')->count() == 1)
                                          		<?php 
												$Job_owner = User::find($job->user_id);
												if ($Job_owner->type=="employer")
												{
													?>
													<a href="#hireCandidateDash-{{$c->id}}" data-success="confirm_hire_rsvp" data-toggle="modal" rel="tooltip" style="vertical-align: middle;"><i class="fa fa-handshake-o pull-right text-primary" data-toggle="tooltip" title="Confirm Hire Details"></i></a>
                                           			<div class="modal fade" tabindex="-1" role="dialog" id="hireCandidateDash-{{$c->id}}" style="font-size: 14px;">
													<div class="modal-dialog">
													  <div class="modal-content">
														  <div class="modal-header">
															  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">×</span></button>
															  <h4 class="modal-title">Hire Candidate</h4>
														  </div>
														  <div class="modal-body">
															  <form action="{{ admin_url('jobs/confirm_hired/'.$job->id.'/'.$c->id) }}" method="post" id="requestPaymentForm">
																  @if(isset($c->hire_details) && $c->hire_details->count())
																	  @foreach($c->hire_details as $hire_detail)
																		  <h5>Candidate Reported Hired by: {{ $hire_detail->added_by()->first()->first_name.' '.$hire_detail->added_by()->first()->last_name}}</h5>
																		  <dl class="pull-right">
																			  <dt><i class="fa fa-dollar"></i> Base Salary</dt>
																			  <dd>{{ $hire_detail->base_salary }}</dd>
																		  </dl>
																		  <dl>
																			  <dt><i class="fa fa-calendar"></i> Start Date</dt>
																			  <dd>{{ date('d M, Y', strtotime($hire_detail->start_date)) }}</dd>
																		  </dl>
																		  @if($hire_detail->additional_info != '')
																			<dl>
																				<dt><i class="fa fa-info-circle"></i> Additional Info</dt>
																				<dd>{{ $hire_detail->additional_info }}</dd>
																			</dl>
																		  @endif
																		  <hr>
																	  @endforeach
																	  <p>If the new hire details have changed, enter the corrected details below.</p>
																  @endif

																  <ul>
																	  @include('jobs._hired_candidates', ['hired_candidate' => $c])
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
													<?php
												}
												else
												{
													?>
													<i class="fa fa-handshake-o pull-right text-primary" data-toggle="tooltip" title="Reported as Hired"></i>
													<?php
												}
												?>
                                            @elseif($c->client_accepted)
                                                <i class="fa fa-{{ ($c->client_accepted == 1) ? 'check text-success' : 'times-circle text-danger' }} pull-right" data-toggle="tooltip" title="Candidate {{ ($c->client_accepted == 1) ? 'Accepted' : 'Rejected' }}"></i>
                                            @else
                                                <i class="fa fa-exclamation-circle pull-right text-warning" data-toggle="tooltip" title="Pending Approval"></i>
                                            @endif
                                            </div>
                                        <a href="{{ admin_url('jobs/candidate_detail/'.$job->id.'/'.$c->id) }}"  style="width: 90%;">
                                            <i class="fa fa-user"></i> {{ $c->name }}
                                        </a>
                                            
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-info">No candidates to view at this time.</p>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-building"></i> Assign Agency</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
<!--                       <form action="{{ admin_url('jobs/assign_agency/'.$job->id) }}" method="post" class="form-horizontal validateForm">-->
                       <form action="{{ admin_url('jobs/assign_agency/'.$job->id) }}" method="post" class="form-horizontal">       
                               {{ flash_msg() }}
                                {{ generate_form_fields($assignAgencyFields, 3) }}
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <button type="submit" class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
                            </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                
                <?php $userType = "admin"; ?>
                @include('jobs._accepted_agencies_admin')
            </div>
            <div class="col-md-5">
                @if(isset($candidate))
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $candidate->name }}</h3>
                            <!-- /.box-tools -->
                            <div class="box-tools pull-right">
                                <div class="has-feedback">
                                    <label class="label label-success">Active</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="{{ base_url('public/img/default_profile_pic.jpg')}}" alt="User Image">
                                    <span class="username">
                                        <a href="#">{{ $candidate->name }}</a>
                                        <span class="pull-right">
                                            @if($candidate->hired)
                                                <label class="label label-success" data-toggle="tooltip" title="Candidate Hired for Job">Hired</label>
                                            @elseif($candidate->client_accepted)
                                                <label class="label label-{{ ($candidate->client_accepted == 1) ?  'success' : 'danger' }}">{{ ($candidate->client_accepted == 1) ? 'Accepted' : 'Rejected'  }}</label>
                                            @else
                                                <label class="label label-warning" data-toggle="tooltip" title="Pending">Pending</label>
                                            @endif
                                        </span>
                                    </span>
                                    <span class="description"><i class="fa fa-building margin-r-5"></i>
                                        <a href="">{{ $candidate->agency()->first()->user_profile()->first()->company_name }}</a>
                                    </span>
                                    <span class="description pull-right"><i class="fa fa-calendar margin-r-5"></i>
                                        {{ date('d M,Y', strtotime($candidate->updated_at ))}}

                                        @if($candidate->client_accepted == 1 && $candidate->hire_details()->where('hire_cancelled','!=','1')->count() == 1)
                                        <a href="#hireCandidateDash-{{$candidate->id}}" data-toggle="modal" rel="tooltip" title="Confirm Hire Details" class="pull-right btn-box-tool hired-candidate"><i class="fa fa-handshake-o fa-2x" style="color: #337ab7"></i></a>
                                        @endif
                                    </span>


                                </div>
                            </div>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#info" data-toggle="tab"><i class="fa fa-info-circle"></i> Info</a></li>
                                    <li><a href="#recruiter-notes" data-toggle="tab"><i class="fa fa-file"></i> Interview Notes</a></li>
                                    <li><a href="#attachments" data-toggle="tab"><i class="fa fa-paperclip"></i> Resume</a></li>
                                    <li><a href="#employer-feedback" data-toggle="tab"><i class="fa fa-comments"></i> Candidate Discussion</a></li>     
                                    <li><a href="#activity-timeline" data-toggle="tab"><i class="fa fa-clock-o"></i> Activity Timelin</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="active tab-pane" id="info">
                                        <div class="row">
                                            <div class="col-md-3 text-left">
                                                <strong><i class="fa fa-phone margin-r-5"></i> Phone </strong>
                                                @if($candidate->phone != '')
                                                    <p class="text-muted">{{ $candidate->phone; }}</p>
                                                @else
                                                    <p class="text-muted">Confidential</p>
                                                @endif
                                            </div>

                                            <div class="col-md-3 text-center">
                                                <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                                                @if($candidate->email != '')
                                                    <p class="text-muted"><a href="#" id="user_email" data-toggle="modal" data-target="#send_email" data-mailto="{{ $candidate->email; }}">{{ $candidate->email; }}</a></p>
                                                @else
                                                    <p class="text-muted">Confidential</p>
                                                @endif
                                            </div>

                                            <div class="col-md-3 text-right">
                                                <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                                                <p class="text-muted">{{ $candidate->city; }}{{ ($candidate->state()->first() != '') ? ', '.$candidate->state()->first()->abbreviation : ''}}</p>
                                                
                                            </div>

                                            <div class="col-md-3 text-center">
                                                    <strong><i class="fa fa-map-marker margin-r-5"></i> Will Relocate</strong>
                                                    <p class="text-muted">{{ $candidate->will_relocate; }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <dl>
                                            <dt><i class="fa fa-history margin-r-5"></i> Candidate Summary</dt>


                                            <dd>   
                                                <iframe src="<?php echo admin_url().'/jobs/employmenthistory_pdf/'.$candidate->id; ?>"   width="100%" style="height:40em" > </iframe>
                                            </dd>

                                            <dt>Notes</dt>
                                            <dd>{{ $candidate->notes; }}</dd>
                                        </dl>
                                    </div>

                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="recruiter-notes">
                                        <div class="box-footer box-comments {{ ($hasNotes = $candidate->notes()->count()) ? '' : 'hide'  }}">
                                            @if($hasNotes)
                                                @each('partials._note', $candidate->notes()->with('added_by')->orderBy('updated_at', 'desc')->get(), 'note')
                                            @endif
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="attachments">
                                        <ul class="list-group list-inline">
                                            @if($candidate->resume)
                                            <li class="list-group-item">
                                                <a href="javascript:void(0)"  iframe-data="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidate->resume) }}&embedded=true" download-data="{{ base_url('public/uploads/docs/'.$candidate->resume) }}" class="files_btn">
                                                    <!-- <i class="fa fa-file-pdf-o fa-3x"></i> -->
                                                    <small class="text-info">{{$candidate->resume}}</small>
                                                </a>
                                            </li>
                                            @endif

                                            <?php $candidatesdocuments = Candidate_documents::where(['candidate_id' => $candidate->id])->get(); ?>
                                            @foreach ($candidatesdocuments as $candidatesdocument)
                                            <li class="list-group-item">
                                            
                                                <a href="javascript:void(0)" iframe-data="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidatesdocument->title) }}&embedded=true" download-data="{{ base_url('public/uploads/docs/'.$candidatesdocument->title) }}" class="files_btn">                          
                                                <small class="text-info">{{$candidatesdocument->title}}</small>
                                                </a>
                                                <br>
                                            </li>
                                            @endforeach  
                                        </ul>

                                        <div class="tab-content">
                                            <div class="tab-pane active">
                                                <dl>
                                                    <dt style="float:right; padding-right:3px; font-size:20px;">
                                                        <a href="{{ base_url('public/uploads/docs/'.$candidate->resume) }}" title="Download File" id="download_link_url" download><i class="fa fa-download"></i></a> 
                                                    </dt>
                                                    <dd>   
                                                        <iframe src="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidate->resume) }}&embedded=true"  width="100%" style="height:40em" id="replace-iframe-url">
                                                        </iframe>
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>


                                        <script type="text/javascript">
                                            $(".files_btn").click(function(){
                                                $('#replace-iframe-url').attr("src", $(this).attr('iframe-data'));
                                                $('#download_link_url').prop("href", $(this).attr('download-data'))
                                            })
                                        </script>
                                    </div>

                                    <div class="tab-pane" id="employer-feedback">
                                        <div class="box-footer box-comments {{ ($hasFeedback = $candidate->feedback()->count()) ? '' : 'hide'  }}">
                                            @if($hasFeedback)
                                                @each('partials._note', $candidate->feedback()->with('added_by')->orderBy('updated_at', 'desc')->get(), 'note')
                                            @else
                                                <p>No Feedback has been added yet</p>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane" id="activity-timeline">

                                        <table width="100%">
                                        <tr><td><br></td><td></td></tr>
                                            <tr>
                                                <td valign="top" width="280">Candidate Submitted for Consideration</td><td><i>{{ date('d M, Y', strtotime($candidate->created_at))}}</i></td>

                                            </tr>
                                            <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>

                                            @if($candidate->client_accepted == 1)

                                                @if(isset($candidate->candidate_rejected_at))
                                                    <tr>
                                                        <td valign="top" width="280">Candidate Rejected</td><td><i>{{ date('d M, Y', strtotime($candidate->candidate_rejected_at))}}</i></td>
                                                    </tr>
                                                    <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                                @endif

                                                <tr>
                                                    <td valign="top" width="280">Candidate Accepted</td><td><i>{{ date('d M, Y', strtotime($candidate->client_accepted_at))}}</i></td>
                                                </tr>
                                                <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                            @endif  

                                            @if($candidate->client_accepted == -1)
                                                @if(isset($candidate->client_accepted_at))
                                                    <tr>
                                                        <td valign="top" width="280">Candidate Accepted</td><td><i>{{ date('d M, Y', strtotime($candidate->client_accepted_at))}}</i></td>
                                                    </tr>
                                                    <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                                @endif
                            
                                                <tr>
                                                    <td valign="top" width="280">Candidate Rejected</td><td><i>{{ date('d M, Y', strtotime($candidate->updated_at))}}</i></td>
                                                </tr>
                                                <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                            @endif 

                                            @if($candidate->hire_details()->where('hire_cancelled','!=','1')->count() == 1)
                                                <?php $candidatehires = Hire_details::where('type','=','Request Payment')->find($candidate->id); ?>
                                                @if(isset($candidatehires))
                                                    <tr>
                                                        <td valign="top" width="280">Reported Hired by the Agency</td><td><i>{{ date('d M, Y', strtotime(Hire_details::where('type','=','Request Payment')->find($candidate->id)->created_at))}}</i></td>
                                                    </tr>

                                                    <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                                @endif 
                                            @endif 
                         

                                            <?php 
                                                if($candidate->hired == 1)
                                                {
                                            ?>
                                            <tr>
                                            <td valign="top" width="280">Reported Hired</td><td><i>
                                            {{ date('d M, Y', strtotime(Hire_details::where('type','=','Hire')->find($candidate->id)->created_at))}}
                                            </i></td></tr>
                                            <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                            <?php
                                                }
                                                else
                                                {}
                                            ?>
                                                    
                                            <?php
                                                if (Hire_details::where('type','=','Hire')->where('candidate_id','=',$candidate->id)->count() > 0)
                                                {
                                            ?>
                                            <tr>
                                            <td valign="top" width="280">Hire Details Updated</td><td><i>
                                            {{ date('d M, Y', strtotime(Hire_details::where('type','=','Hire')->find($candidate->id)->updated_at))}}
                                            </i></td></tr>
                                            <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                            <?php
                                                }
                                                else
                                                {}
                                            ?>
                                            
                                            <?php
                                                if (Hire_details::where('type','=','Hire')->where('candidate_id','=',$candidate->id)->count() > 0)
                                                {
                                            ?>
                                                <tr>
                                                <td valign="top" width="280">Hire Details Edited by</td><td><i>
                                            
                                            <?php
                                                $name1 = User::find(Hire_details::where('type','=','Hire')->find($candidate->id)->added_by);
                                                echo($name1->first_name." ".$name1->last_name);
                                            ?>
                                            </i></td></tr>
                                            <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                            <?php
                                                }
                                                else
                                                {}
                                            ?>

                                            <?php
                                                if (Candidate_feedback::where('candidate_id','=',$candidate->id)->count() > 0)
                                                {
                                            ?>
                                            <tr>
                                            <td valign="top" width="280">Candidate Discussions</td><td><i>
                                            <?php
                                                $myRsltSet =Candidate_feedback::where('candidate_id','=',$candidate->id)->orderBy('id', 'desc')->get(); 
                                                foreach($myRsltSet AS $myRow)
                                                {
                                            ?>
                                            {{ date('d M, Y', strtotime($myRow['created_at']))}}<br>
                                            <?php
                                                }
                                            ?>
                                            {{ date('d M, Y', strtotime(Candidate_feedback::orderBy('id', 'desc')->find($candidate->id)->created_at))}}

                                            </i></td></tr>
                                            <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                                            <?php
                                                }
                                                else
                                                {}
                                            ?>

                                            <?php
                                                if (Hire_details::where('candidate_id','=',$candidate->id)->where('hire_cancelled','=',1)->count() > 0)
                                                {
                                            ?>
                                            <tr>
                                            <td valign="top" width="280">New Hire Details Cancelled by</td><td><i>
                                            <?php
                                                $hired_cancelled_by = User::find(Hire_details::where('candidate_id','=',$candidate->id)->where('hire_cancelled','=',1)->first()->hire_cancelled_by);
                                                echo($hired_cancelled_by->first_name." ".$hired_cancelled_by->last_name);
                                            ?>
                                            </i></td></tr>
                                            <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>

                                            <tr><td valign="top" width="280">New Hire Details Cancelled at</td><td><i>
                                            <?php
                                                $hired_cancelled_at = Hire_details::where('candidate_id','=',$candidate->id)->where('hire_cancelled','=',1)->first()->hire_cancelled_at;
                                            ?>
                                            {{ date('d M, Y', strtotime($hired_cancelled_at))}}
                                            </i></td></tr>
                                            <tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>

                                            <?php
                                                }
                                                else
                                                {}
                                            ?>
                                        </table>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                        </div>
                    </div>
                    <!-- /. box -->
                @else
                    <div class="callout callout-info">
                        <p>Select a candidate to view Details</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection


@section('page-js')
    <script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>
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

@section('end-script')
    <script  type="text/javascript">
		$('.select2').select2({
            width: '100%',
            placeholder : 'Select Agency',
			paddingLeft:'0px'
			
        });
        var toggleDisplay = $('.toggle-display');
        toggleDisplay.on('click',function (e) {
            var text = toggleDisplay.text();
            $('#job-details').slideToggle(400, function () {
                if(text=='Show'){
                    toggleDisplay.text('Hide');
                }else{
                    toggleDisplay.text('Show');
                }
            });
        });
    </script>
@endsection