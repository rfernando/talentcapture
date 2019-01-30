@section('page-css')
@parent

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ admin_assets_url('plugins/datepicker/datepicker3.css') }}">
@endsection

<div class="modal" id="sendMsg">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-envelope"></i> Send a Message</h4>
            </div>
            <div class="modal-body">
                <form action="{{ base_url('messages/send') }}" method="post" id="chatForm" >
                    <div class="form-group">
                        <input type="hidden" name="to_user_id" value="{{ $candidate->agency()->first()->id }}">
                        <textarea name="text" placeholder="Type Message ..." class="form-control" rows="10"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" data-submit="#chatForm">Send</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@if( $candidate->client_accepted)
    <div class="modal fade" tabindex="-1" role="dialog" id="hireCandidate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Hire Candidate</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ base_url('jobs/hire_candidates/'.$job->id.'/'.$candidate->id) }}" method="post" id="requestPaymentForm">
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
@endif

<div class="modal fade" tabindex="-1" role="dialog" id="send_email">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('dashboard_widgets._quick_email')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="box box-primary"  id="candi_box">
    <div class="box-header with-border">
        <h3 class="box-title"><?php /*?>{{ $candidate->name }}<?php */?></h3>
        <span class="box-title" style="font-size: 16px;"><i class="fa fa-building margin-r-5"></i>
            <a href="{{ base_url('agency/detail/'.$candidate->agency()->first()->id) }}" target="_blank">Represented By: {{$candidate->agency()->first()->first_name}} {{$candidate->agency()->first()->last_name}} @ {{ $candidate->agency()->first()->user_profile()->first()->company_name }}</a>
        </span>
        <!-- /.box-tools -->
        <div class="box-tools pull-right">
            <div class="has-feedback">
                @if($candidate->hired)
                    <label class="label label-success" >Candidate Hired</label>
                @elseif($job->closed)
                    <label class="label label-danger" >Candidate Not Selected</label>
                @elseif($candidate->client_accepted)
                    <label class="label label-{{ ($candidate->client_accepted == 1) ? 'success' : 'danger' }}" >Candidate {{ ($candidate->client_accepted == 1) ? 'Accepted' : 'Rejected' }}</label>
                @else
                    <label class="label label-warning" >Pending Approval</label>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {{ flash_msg() }}
        <div class="post">
            <div class="user-block">
                <img class="img-circle img-bordered-sm" src="{{ base_url('public/img/default_profile_pic.jpg')}}" alt="User Image">
                <span class="usernamenew">
                    <a href="#">{{ $candidate->name }}&nbsp;&nbsp;&nbsp;&nbsp;</a>
                    @if($candidate->linkedin_url != '')
                        <span>
                            <a href="{{$candidate->linkedin_url}}" target="_blank"><i class="fa fa-linkedin-square margin-r-5 fa-lg"></i></a>
                        </span>
                    @endif

                    @if($candidate->facebook_url != '')
                        <span>
                            <a href="{{$candidate->facebook_url}}" target="_blank"><i class="fa fa-facebook-square margin-r-5 fa-lg"></i></a>
                        </span>
                     @endif
                    <span class="pull-right">
                        @unless($job->closed || $candidate->hired != NULL || $candidate->client_accepted < 0)

                            @if($candidate->client_accepted)
                                @unless($hasHireDetails)
                                    <a href="{{ base_url('jobs/candidate_rsvp/'.$candidate->id.'/reject') }}" data-success="update_rsvp" data-toggle="tooltip" title="Decline Candidate" class="pull-right btn-box-tool ajax reject-candidate"><i class="fa fa-times fa-2x"></i></a>
                                @endunless
                                @if($hasHireDetails)
                                    <a href="#hireCandidate" data-toggle="modal" rel="tooltip" title="{{ ($hasHireDetails) ? 'Confirm Hire Details' : 'Hire Candidate' }}" class="pull-right btn-box-tool {{ ($hasHireDetails) ? 'hired-candidate ' : 'hire-candidate' }}"><i class="fa fa-handshake-o fa-2x" style="color: #337ab7;"></i></a>
                                @else
                                    <a href="#hireCandidate" data-toggle="modal" rel="tooltip" title="{{ ($hasHireDetails) ? 'Confirm Hire Details' : 'Hire Candidate' }}" class="pull-right btn-box-tool {{ ($hasHireDetails) ? 'hired-candidate ' : 'hire-candidate' }}"><i class="fa fa-handshake-o fa-2x"></i></a>
                                @endif
                            @else
                                <a href="{{ base_url('jobs/candidate_rsvp/'.$candidate->id.'/reject') }}" data-success="update_rsvp" data-toggle="tooltip" title="Reject Candidate" class="pull-right btn-box-tool ajax reject-candidate"><i class="fa fa-thumbs-down fa-2x"></i></a>
                                <a href="{{ base_url('jobs/candidate_rsvp/'.$candidate->id.'/accept') }}" data-success="update_rsvp" data-toggle="tooltip" title="Accept Candidate" class="pull-right btn-box-tool ajax accept-candidate"><i class="fa fa-thumbs-up fa-2x"></i></a>
                            @endif
                        @endunless
                            @if($candidate->hired)
                            <a href="#hireCandidate" data-toggle="modal" rel="tooltip" title="Modify Hire Details" class="pull-right btn-box-tool {{ ($hasHireDetails) ? 'hired-candidate ' : 'hire-candidate' }}"><i class="fa fa-handshake-o fa-2x"></i></a>
						
                            @endif
                            <a href="{{ base_url('messages/chat/'.$candidate->agency()->first()->id) }}" rel="tooltip" title="Send a Message to Agency" class="pull-right btn-box-tool send-message"><i class="fa fa-envelope  fa-2x"></i></a>
                            @if($candidate->client_accepted == -1)
                            <a href="{{ base_url('jobs/candidate_rsvp/'.$candidate->id.'/accept') }}" data-success="update_rsvp" data-toggle="tooltip" title="Accept Candidate" class="pull-right btn-box-tool ajax accept-candidate"><i class="fa fa-thumbs-up fa-2x"></i></a>
                            @endif
                    </span>
                </span>
                <span class="description pull-right"><i class="fa fa-calendar margin-r-5"></i>
                    {{ date('d M, Y', strtotime($candidate->updated_at ))}}
                </span>
            </div>
        </div>
        <div class="nav-tabs-custom">
            <div class="tab-content">
                <div class="active tab-pane" id="info">
                    @if($candidate->client_accepted == 1)
                        <div class="row">
                            <div class="col-md-3">
                                <strong><i class="fa fa-phone margin-r-5"></i> Phone</strong>
                                <p class="text-muted">{{ $candidate->phone; }}</p>
                            </div>

                            <div class="col-md-3 ">
                                <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                                <p class="text-muted"><a href="#" data-toggle="modal" data-target="#send_email" data-mailto="{{ $candidate->email; }}">{{ $candidate->email; }}</a></p>
                            </div>

                            <div class="col-md-3 text-center">
                                <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                                <p class="text-muted">{{ $candidate->city; }}{{ ($candidate->state()->first() != '') ? ', '.$candidate->state()->first()->abbreviation : ''}}</p>
                            </div>

                            <div class="col-md-3 text-center">
                                <strong><i class="fa fa-map-marker margin-r-5"></i> Will Relocate</strong>
                                <p class="text-muted">{{ $candidate->will_relocate; }}</p>
                            </div>

                        </div>
                        <hr>
                    @endif
                    <dl>
                        <dt><i class="fa fa-history margin-r-5"></i> Candidate Summary</dt>
                         <dd>{{ $candidate->employment_history; }}</dd>
                        <?php /*?><dd>   
                        <iframe src="<?php echo base_url().'jobs/employmenthistory_pdf/'.$candidate->id; ?>"   width="100%" style="height:40em" > </iframe></dd><?php */?>

                        <?php /*?><dt><i class="fa fa-file-text-o margin-r-5"></i> Notes</dt>
                        <dd>{{ $candidate->notes; }}</dd><?php */?>
                    </dl>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="attachments">
                    <ul class="list-group list-inline">
                        @if($candidate->client_accepted == 1 && $candidate->resume)
                            <li class="list-group-item">
                                <a href="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidate->resume) }}&embedded=true" target="_blank">
                                    <i class="fa fa-file-pdf-o fa-3x"></i>
                                </a><br>
                                <small class="text-info">{{$candidate->resume}}</small>
                            </li>

                                    @foreach ($candidatesdocuments as $candidatesdocument)
                                    <li class="list-group-item">
                                        <a href="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidatesdocument->title) }}&embedded=true" target="_blank">
                                            <i class="fa fa-file-pdf-o fa-3x"></i>
                                        </a><br>
                                        <small class="text-info">{{$candidatesdocument->title}}</small>
                                    </li>
                                    @endforeach

                        @else
                            <li>Accept Candidate to view Candidate Resume</li>
                        @endif

                    </ul>

                    @if($user_type == 'agency')
                    <div class="box-body">
                            <form action="{{ base_url('jobs/upload_attachment') }}" method="post" class="form-horizontal validateForm" enctype="multipart/form-data">
                            <input type="hidden" name="candidates[candidatejob_id]" value="{{ $candidate->id."_".$job->id }}">
                            {{ generate_form_fields($candidateFields, 2) }}
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                        <div class="box-body">
                        </div>
                    @endif
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="recruiter-notes">
                    <!--- Check note is empty or not --->

                    @if(!empty($candidate->notes()->with('added_by')->orderBy('updated_at', 'desc')->get()->toArray()))
                        <div class="box-footer box-comments">
                            @each('partials._note', $candidate->notes()->with('added_by')->orderBy('updated_at', 'desc')->get(), 'note')
                        </div>
                    @else
                        <p>No new notes</p>
                    @endif
                </div>

                <div class="tab-pane" id="candidate-feedback">
                    <div class="box-footer box-comments {{ ($hasComments = $candidate->feedback()->count()) ? '' : 'hide' }}">
                        @if($hasComments)
                            @each('partials._note', $candidate->feedback()->with('added_by')->orderBy('updated_at', 'desc')->get(), 'note')
                        @endif
                    </div>

                    <div class="box-footer">
                        <form action="{{ base_url('jobs/add_feedback/'.$candidate->id) }}" class="ajax" data-success="add_note" method="post">
                            @if($userImg = get_user('profile_pic'))
                                <img class="img-responsive img-circle img-sm" src="{{ base_url('public/uploads/'.$userImg) }}" alt="Alt Text">
                            @else
                                <img class="img-responsive img-circle img-sm" src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="Alt Text">
                            @endif
                            <div class="img-push">
                                <input type="text" name="employer_feedback" class="form-control input-sm" placeholder="Press enter to post comment" autocomplete="off">
                            </div>
                        </form>
                    </div>
                </div>
                
                
                 <div class="tab-pane" id="activity-timeline">
                    <table width="100%">
					<tr><td><br></td><td></td></tr>
                    	@if($candidate->client_accepted == 1)
							<tr>
								<td valign="top" width="180">Candidate Accepted</td><td><i>{{$candidate->client_accepted_at}}</i></td>
							</tr>
							<tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
							
						<?php /*?>@else
							<tr>
								<td valign="top" width="180">Candidate Acceptance</td><td><i>Pending</i></td>
							</tr><?php */?>
						@endif
							
						@if($candidate->client_accepted == -1)
							<tr>
								<td valign="top" width="180">Candidate Rejected</td><td><i>{{$candidate->updated_at}}</i></td>
							</tr>
							<tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
                  		@endif	
       
									
									<?php if ($candidate->hired == 1)
									{
										?>
										<tr>
										<td valign="top" width="180">Candidate Reported Hired</td><td><i>
										<?php
										echo Hire_details::where('type','=','Hire')->find($candidate->id)->created_at;
									?>
									</i></td></tr>
									<tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
									<?php
									}
									else
									{
										//echo("Pending");
									}
									?>

									
									<?php
									if (Hire_details::where('type','=','Hire')->where('candidate_id','=',$candidate->id)->count() > 0)
									{
										?>
										<tr>
										<td valign="top" width="180">Hire Details Edited by</td><td><i>
										<?php
										$name1 = User::find(Hire_details::where('type','=','Hire')->find($candidate->id)->added_by);
										echo($name1->first_name." ".$name1->last_name);
										?>
									</i></td></tr>
									<tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
									<?php
									}
									else
									{
										//echo("-");
									}
									?>
									
								
									<?php 
									if(Hire_details::where('type','=','Request Payment')->where('candidate_id','=',$candidate->id)->count() > 0)
									{
										?>
										<tr>
										<td valign="top" width="180">New Hire Details Edited by</td><td><i>
										<?php
										$myRsltSet =Hire_details::where('candidate_id','=',$candidate->id)->where('type','=','Request Payment')->orderBy('id', 'desc')->get(); 
										foreach($myRsltSet AS $myRow)
										{
											$name2 = User::find($myRow->added_by);
											echo($name2->first_name." ".$name2->last_name." - on ".$myRow->updated_at.".<br>");
										}
										?>
									</i></td></tr>
									<tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
									<?php
									}
									else
									{
										//echo("-");
									}
									?>

									<?php 
									if (Recruiter_notes::where('candidate_id','=',$candidate->id)->count() > 0)
									{
										?>
										<tr>
										<td valign="top" width="180">Recruiter Notes Provided</td><td><i>
										<?php
										$myRsltSet =Recruiter_notes::where('candidate_id','=',$candidate->id)->orderBy('id', 'desc')->get(); 
										foreach($myRsltSet AS $myRow)
										{
												echo $myRow['created_at'].".<br>";
										}
										?>
									</i></td></tr>
									<tr><td><hr align="right" width="95%" color="#565656" /></td><td><hr align="left" width="15%" color="#565656" /></td></tr>
									<?php
									}
									else
									{
										//echo("-");
									}
									?>
					 </table>
                </div>
                
                
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
</div>

@section('page-js')
@parent

<!-- Datepicker -->
<script src="{{ admin_assets_url('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection



@section('end-script')
    @parent

    <script type="application/javascript">

        function update_rsvp(result, element) {
            element.parent().html(result.msg);
            window.location.reload();
        }


        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        function add_note(result, element) {
            var commentContainer = element.parent().prev();
            if(commentContainer.hasClass('hide')){
                commentContainer.removeClass('hide')
            }
            commentContainer.prepend(result.msg);

        }
    </script>

    <!--- Start Display Active and Inactive Jobs's List--->
    @if($job->closed)
        <script>
            $('#active').hide();
        </script>
    @else
        <script>
            $('#closed').hide();
        </script>
    @endif
    <!--- End Display Active and Inactive Jobs's List--->

@endsection