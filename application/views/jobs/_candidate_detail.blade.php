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
    <div class="modal fade" tabindex="-1" role="dialog" id="hireCandidate" style="font-size: 14px;">
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
                          
                           <!--Resers the $candidate->hire_details object array-->
                       
							<?php
								$my_count = $candidate->hire_details->count();
								foreach($candidate->hire_details as $hire_detailt1)
								{
									$my_count--;
									$hire_details_reversed[$my_count]=$hire_detailt1;
								}
								$countn2=0;
								while($countn2<$candidate->hire_details->count())
								{
									$hire_details_reversed2[$countn2]=$hire_details_reversed[$countn2];
									$countn2++;
								}
							?>
                            @foreach($hire_details_reversed2 as $hire_detail)
                                <h5>Candidate Reported Hired by: {{ $hire_detail->added_by()->first()->first_name.' '.$hire_detail->added_by()->first()->last_name}} on {{ date('d M, Y', strtotime($hire_detail->created_at)) }}</h5>
                                <dl class="pull-right">
                                    <dt><i class="fa fa-dollar"></i> Base Salary</dt>
                                    <dd>{{ (str_replace(",","",$hire_detail->base_salary)) }}</dd>
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

                            @if($hasHireDetails && !$candidate->hired)
                                <p>Confirm or modify the start date or salary. To confirm the information, enter the same details below. If the new hire information has changed or is incorrect, enter the corrected details.</p>
                            @else
                                <p>If the new hire details have changed, enter the corrected details below.</p>
                            @endif

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
        <span class="box-title"><i class="fa fa-building margin-r-5"></i>
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
                            <a href="{{ ($candidate->client_accepted == 1) ? $candidate->linkedin_url :'javascript:void(0)' }}" target="_blank"><i class="fa fa-linkedin-square margin-r-5 fa-lg"></i></a>
                        </span>
                    @endif

                    @if($candidate->facebook_url != '')
                        <span>
                            <a href="{{($candidate->client_accepted == 1) ? $candidate->facebook_url : 'javascript:void(0)'}}" target="_blank"><i class="fa fa-facebook-square margin-r-5 fa-lg"></i></a>
                        </span>
                     @endif
                     
           <!-- candidate rejected Message -->
			@if(isset($_GET['cddtrejmsg']) && $_GET['cddtrejmsg']=1)
			<span class="pull-right">
				<section class="col-lg-12" id="rejected_message">
					<div class="alert alert-danger alert-dismissible">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						Candidate successfully rejected. We have notified the agency recruiter who represents the candidate.
					</div>
				</section>
			</span>
			<script>
				setTimeout(function () {
							window.location.replace("<?php echo(base_url('jobs/candidate_detail/'.$job->id.'/'.$candidate->id));?>");
						}, 4000);
				</script>
			@endif         <span class="pull-right">
                        @unless($job->closed || $candidate->hired != NULL || $candidate->client_accepted < 0)

                            @if($candidate->client_accepted)
                                @unless($hasHireDetails)
                                    <a href="#" data-toggle="modal" data-target="#reject-reason-options-popup-{{$candidate->id}}" title="Decline Candidate" data-backdrop="static" data-keyboard="false" class="pull-right btn-box-tool reject-candidate"><i class="fa fa-thumbs-down fa-2x"></i></a>

                                @endunless
                                <a href="#hireCandidate" data-toggle="modal" rel="tooltip" title="{{ ($hasHireDetails) ? 'Confirm Hire Details' : 'Hire Candidate' }}" class="pull-right btn-box-tool {{ ($hasHireDetails) ? 'hired-candidate ' : 'hire-candidate' }}"><i class="fa fa-handshake-o fa-2x" style="{{ ($hasHireDetails) ? 'color: #337ab7' : '' }}"></i></a>
                            @else
                                <!-- <a href="{{ base_url('jobs/candidate_rsvp/'.$candidate->id.'/reject') }}" data-success="update_rsvp" data-toggle="tooltip" title="Reject Candidate" class="pull-right btn-box-tool ajax reject-candidate"><i class="fa fa-thumbs-down fa-2x"></i></a>-->

                                <a href="#" data-toggle="modal" data-target="#reject-reason-options-popup-{{$candidate->id}}" title="Reject Candidate" data-backdrop="static" data-keyboard="false" class="pull-right btn-box-tool reject-candidate"><i class="fa fa-thumbs-down fa-2x"></i></a>

                                <a href="{{ base_url('jobs/candidate_rsvp/'.$candidate->id.'/accept') }}" data-success="update_rsvp" data-toggle="tooltip" title="Accept Candidate" class="pull-right btn-box-tool ajax accept-candidate"><i class="fa fa-thumbs-up fa-2x"></i></a>
                            @endif

                            <!--  Model Pop-Up For Reject Reason -->
                              <div class="modal fade" tabindex="-1" role="dialog" id="reject-reason-options-popup-{{$candidate->id}}">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Select Reject Reason</h4>
                                    </div>
                                    <form action="{{ base_url('jobs/candidate_rsvp/'.$candidate->id.'/reject') }}" method="post" id="requestPaymentForm">
                                      <div class="modal-body">
                                        <div class="row">
                                          <div class="col-xs-12">
                                           <input type="hidden" name="reject_return_path" value="jobs" />
                                            <select class="form-control" name="reject_reason" required onchange="getothersval(this);">
                                              <option value="">Select reject reason</option>
                                              @foreach ($reject_reasons as $reasons) 
                                                <option value="{{ $reasons['reject_option'] }}">{{ $reasons['reject_option'] }}</option>
                                              @endforeach
                                              <option value="other">Other</option>
                                            </select>
                                            <span id="other_input_field"></span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                          <input type="submit" class="btn btn-primary" value="Submit"> 
                                      </div>
                                    </form>
                                  </div>                                             
                                </div>
                              </div>
                              <!-- End Model Pop-Up For Reject Reason -->

                        @endunless
                            @if($candidate->hired)
                            <a href="#hireCandidate" data-toggle="modal" rel="tooltip" title="Modify Hire Details" class="pull-right btn-box-tool {{ ($hasHireDetails) ? 'hired-candidate ' : 'hire-candidate' }}"><i class="fa fa-handshake-o fa-2x text-success"></i></a>
						
                            @endif
                           <!--  Add job id  $job->id in link -->
                            <div class="box pull-right" style="width: 40px;height: 20px;border: none;background: none; margin-top: 5px;">
                                <a href="#" class="dropdown-toggle btn-box-tool send-message" data-toggle="dropdown">
                                    <i class="fa fa-envelope fa-2x"></i>
                                </a>

                                <ul class="dropdown-menu" style="min-width: 160px">
                                    <li>
                                        <ul class="menu" style="margin: 0px;padding: 0px;list-style: none;">
                                            <li style="padding: 10px;">
                                               <a href="#" rel='tooltip' onclick="select_candidate_messaging_tab()" data-toggle="tooltip" title="Message Candidate"  style="margin-right: 5px;">Message Candidate</a>
                                            </li>
                                            <li style="padding: 10px;">
                                               <a href="{{ base_url('messages/chat/'.$agencies[0]['id'].'/'.$candidate->id.'/'.$job->id) }}" data-toggle="tooltip" rel='tooltip' data-toggle="tooltip" title="Message Recruiter"  style="margin-right: 5px;">Message Recruiter</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            @if($candidate->client_accepted == -1)  <!-- fixing the candidate accpet icon for the rejected candidate -->
                             <a href="{{ base_url('jobs/candidate_rsvp/'.$candidate->id.'/accept') }}" data-success="update_rsvp" data-toggle="tooltip" title="Accept Candidate" class="pull-right btn-box-tool ajax accept-candidate"><i class="fa fa-thumbs-up fa-2x"></i></a>
                             @endif  <!-- Adding to accept candidate for the rejected candidate FOR RP-784 -->
                    </span>
                </span>

                <span class="description pull-right"><i class="fa fa-calendar margin-r-5"></i>
                    {{ date('d M, Y', strtotime($candidate->updated_at ))}}
                </span>
                
            </div>
        </div>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="candidateProfileTabs"> <!-- fixing candidate profile tabs to fixed size -->
                <li class="active"><a href="#info" data-toggle="tab"><i class="fa fa-info-circle fa-2x" data-toggle="tooltip" title data-original-title="Info"></i></a></li>
                <li><a href="#recruiter-notes" data-toggle="tab"><i class="fa fa-file fa-2x" data-toggle="tooltip" title data-original-title="Interview Notes"></i></a></li>
                <li><a href="#attachments" data-toggle="tab"><i class="fa fa-paperclip fa-2x" data-toggle="tooltip" title data-original-title="Documents"></i></a></li>
                
                 <li>
                    <a href="#candidate_messaging" data-toggle="tab" id="send_mail_to_candidate"><i class="fa fa-envelope fa-2x" data-toggle="tooltip" title data-original-title="Candidate Messaging"></i> 
                    </a>
                </li>
                <li>
                    <a href="#candidate-feedback" data-toggle="tab" id="discuss_click"><i class="fa fa-comments fa-2x" data-toggle="tooltip" title data-original-title=" <?php 
                        // Chanage Candidate Discussion to Recruiter Messaging using given condition 
                      $candidate_owner= (null !== (User::where(['id' => $candidate->user_id])->lists('type')['0'])) ? User::where(['id' => $candidate->user_id])->lists('type')['0']: '';
                        if((get_user('type')=='employer' && $candidate_owner=='agency') || (get_user('type')=='agency' && $candidate_owner=='agency')){
                            echo "Recruiter Messaging";
                        }
                        ?>"></i> 
                    </a>
                </li>


                <li><a href="#activity-timeline" data-toggle="tab"><i class="fa fa fa-clock-o fa-2x" data-toggle="tooltip" title data-original-title="Activity Timeline"></i></a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="info">
                    @if($candidate->client_accepted == 1)
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <strong><i class="fa fa-phone margin-r-5"></i> Phone</strong>
                                <p class="text-muted"><a href="tel:{{ $candidate->phone; }}"> {{ $candidate->phone; }} </a></p>
                            </div>

                            <div class="col-md-3 text-left">
                                <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                                <p class="text-muted"><a href="#" onclick="select_candidate_messaging_tab()">{{ $candidate->email; }}</a></p>
                            </div>

                            <div class="col-md-3 text-left">
                                <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                                <p class="text-muted">{{ $candidate->city; }}{{ ($candidate->state()->first() != '') ? ', '.$candidate->state()->first()->abbreviation : ''}}</p>
                            </div>

                            <div class="col-md-3 text-left">
                                <strong><i class="fa fa-map-marker margin-r-5"></i> Will Relocate</strong>
                                <p class="text-muted">{{ $candidate->will_relocate; }}</p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3 text-left">
                                    <strong><i class="fa fa-dollar margin-r-5"></i> Desired Salary</strong>
                                    <p class="text-muted">{{ $candidate->salary; }}</p>
                            </div>
                            <div class="col-md-3 text-left">
                                    <strong><i class="fa fa-home margin-r-5"></i> Residency</strong>
                                    <p class="text-muted">{{ $candidate->residency; }}</p>
                            </div>
                        </div>
                        <hr>
                    @endif
                    <dl>
                        <dt><i class="fa fa-history margin-r-5"></i> Candidate Summary</dt>
                        <!-- <dd>{{ $candidate->employment_history; }}</dd>-->
                        <dd>   <iframe src="<?php echo base_url().'jobs/employmenthistory_pdf/'.$candidate->id; ?>"   width="100%" style="height:40em" > </iframe></dd>

                        <?php /*?><dt><i class="fa fa-file-text-o margin-r-5"></i> Notes</dt>
                        <dd>{{ $candidate->notes; }}</dd><?php */?>
                    </dl>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="attachments">
                    @if($candidate->client_accepted == 1)
                        <ul class="list-group list-inline">
                            @if($candidate->resume)
                            <li class="list-group-item">
                                <a href="javascript:void(0)" iframe-data="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidate->resume) }}&embedded=true" download-data="{{ base_url('public/uploads/docs/'.$candidate->resume) }}" class="files_btn">
                                    <small class="text-info">{{$candidate->resume}}</small>
                                </a><br>
                                
                            </li>
                            @endif
                            
                            @foreach ($candidatesdocuments as $candidatesdocument)
                            <li class="list-group-item">
                                <a href="javascript:void(0)" iframe-data="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidatesdocument->title) }}&embedded=true" download-data="{{ base_url('public/uploads/docs/'.$candidatesdocument->title) }}" class="files_btn">
                                    <small class="text-info">{{$candidatesdocument->title}}</small>
                                </a><br>
                                
                            </li>
                            @endforeach
    					</ul>

                        <div class="tab-content">
                            <div class="active tab-pane">
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

                    @else
                    <ul class="list-group list-inline">
                        <li>The candidate must be accepted first before you can view supporting documents.</li>
                    </ul>
                    @endif
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane" id="recruiter-notes">
                    <div class="box-footer">
                            <form action="{{ base_url('searches/add_note/'.$candidate->id) }}" class="ajax" data-success="add_note_interview" method="post">
                            @if($userImg = get_user('profile_pic'))
                                <img class="img-responsive img-circle img-sm" src="{{ base_url('public/uploads/'.$userImg) }}" alt="Alt Text">
                            @else
                                <img class="img-responsive img-circle img-sm" src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="Alt Text">
                            @endif
                            <div class="img-push">
                                <textarea rows="6" name="recruiter_note" class="form-control input-sm" placeholder="Press enter to post comment" autocomplete="off"></textarea>
                                <br>
                                <input class="btn btn-primary" type="submit" value="Send">
                            </div>
                        </form>
                    </div>
                    <div class="box-footer box-comments {{ ($hasNotes = $candidate->notes()->count()) ? '' : 'hide'  }}" id="display_add_notes_intrvw_note">
                        @if($hasNotes)
                            @each('partials._note', $candidate->notes()->with('added_by')->orderBy('updated_at', 'desc')->get(), 'note')
                        @endif
                    </div>

                </div>

                <div class="tab-pane" id="candidate-feedback">
                    
                    <div class="box-footer">
                        <form action="{{ base_url('jobs/add_feedback/'.$candidate->id) }}" class="ajax" data-success="add_note" method="post">
                            @if($userImg = get_user('profile_pic'))
                                <img class="img-responsive img-circle img-sm" src="{{ base_url('public/uploads/'.$userImg) }}" alt="Alt Text">
                            @else
                                <img class="img-responsive img-circle img-sm" src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="Alt Text">
                            @endif
                            <?php
                                $recruiter_placeholder = Site_messages::where('type','=','recruiter_messaging_placeholder')->first();
                            ?>
                            <div class="img-push">
                                <input type="hidden" name="job_provider"  id="job_provider" value="<?=$candidate->agency()->first()->id;?>">
                                <input type="hidden" name="job_id"  id="job_id" value="<?=$candidate->job_id;?>">
                                <textarea onKeyUp="discussion_submit_validate()" rows="6" name="employer_feedback" id="employer_feedback" class="form-control input-sm" placeholder="<?php echo $recruiter_placeholder->msg; ?>" autocomplete="off"></textarea><br>
                                <input id="discussion_submit" disabled class="btn btn-primary" type="submit" value="Send">
                            </div>
                        </form>
                    </div>
                    <div class=" box-footer box-comments {{ ($hasComments = $candidate->feedback()->count()) ? '' : 'hide' }}" id="display_add_notes_rec_msg">
                        @if($hasComments)
                            @each('partials._note', $candidate->feedback()->with('added_by')->orderBy('updated_at', 'desc')->get(), 'note')
                        @endif
                    </div>

                </div> 

                <!-- new section: candidate messaging  -->
                <div class="tab-pane" id="candidate_messaging">
                    <div class="box-footer">
                        @if($candidate->client_accepted == 1)
                        <a id="read_candidate_reply" href="{{ base_url('jobs/update_latest_messaging/'.$candidate->job_id.'/'.$candidate->id) }}" class="ajax" data-success="add_note_candidate" style="display: none;"></a>
                        <form action="{{ base_url('jobs/send_mail_to_candidate/'.$candidate->id) }}" class="ajax" id="template_form" data-success="add_note_candidate" method="post">
                            <div class="img-push">
                                <input type="hidden" name="candidate_id"  id="candidate_id" value="<?=$candidate->id;?>">
                                <input type="hidden" name="job_id"  id="job_id" value="<?=$job->id;?>">
                                <?php if (!$conversation_data->count()) { ?>
                                <div class="form-group">                             
                                <select class="form-control form-rounded" id="insert_template" name="template" onchange="getMessageTemplate();">
                                <option value="" selected>Insert Template</option>
                                <?php if (!empty($message_template)) {
                                    foreach ($message_template as $template) { ?>
                                        <option value="<?php echo $template->id;?>"><?php echo $template->template_name; ?></option>
                                <?php 
                                        }
                                    }
                                ?>
                                </select>
                                </div>
                                <div class="form-group">
                                <label for="pwd">Subject:</label>
                                 <input type="text" class="form-control form-rounded" id="subject" name="subject">
                                </div>

                                <?php 
                                }
                                else {?>
                                    <input type="hidden" name="subject"  id="subject" value="<?=$old_subject;?>">
                                <?php    
                                }
                                ?>

                                <?php
                                    $messaging_placeholder = Site_messages::where('type','=','candidate_messaging_hiring_manager_placeholder')->first();
                                ?>

                                <textarea rows="6" name="last_conversation" id="last_conversation" class="form-control input-sm" placeholder="<?php echo $messaging_placeholder->msg; ?>" autocomplete="off"></textarea><br>
                                <?php if (!$conversation_data->count()) { ?>
                                <div class="checkbox">
                                <input type="hidden" name="save_template" value="0" />
                                <label class="checkbox-temp">
                                    <input type="checkbox" value="1" name="save_template" id="save_template" onclick="addSubject();">Save as template 
                                </label>
                                <label class="input-temp">                                
                                    <input type="text" class="form-rounded form-temp" style="display: none;" name="template_name">
                                </label>
                                </div>
                                <?php 
                                }
                                ?>
                                <br>
                                <input id="send_mail_submit" class="btn btn-primary" type="submit" name="send_mail" onclick="send_loader();" value="Send Mail">
                                <div class="send-loader" style="display: none;"><img src="{{ base_url('public/img/loader.gif') }}" height="30"></div>
                        </form>
                    </div>

                    <div class="box-footer box-comments {{ ($hasComments1 = $conversation_data->count()) ? '' : 'hide' }}" id="display_candidate_rec_msg" style="margin-top: 20px;">
                     @if($hasComments1)
                            @each('partials._coversation',$conversation_data, 'coversation')
                        @endif
                    </div>
                    @else
                    <ul class="list-group list-inline">
                        <li>You can only send messages to accepted candidates.</li>
                    </ul>
                    @endif
                </div>
                </div>              
                
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

@section('page-js')
@parent

<!-- Datepicker -->
<script src="{{ admin_assets_url('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
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
<script type="text/javascript">

</script>
@endsection

@section('end-script')
    @parent

    <script type="application/javascript">
		//enable send button for Candidate Discussion
		function discussion_submit_validate()
		{
			var comment_text = document.getElementById("employer_feedback").value.trim();
			if (comment_text.length>0)
				{
					document.getElementById("discussion_submit").disabled=false;
				}
			else
				{
					document.getElementById("discussion_submit").disabled=true;
				}
		}

        var config = {};

        config.extraPlugins='confighelper';

        $(function () {
            CKEDITOR.replace('last_conversation',config);
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
            /*var mail_body    = document.getElementById("last_conversation").value.trim();
            if (mail_body<1)
            {
                document.getElementById("send_mail_submit").disabled=true; 
            }
            else
            {
                document.getElementById("send_mail_submit").disabled=false;
            }*/
        }
		
		//trigger tab click on Candidate Discussion
		
		function discussion_click()
		{
			$('#discuss_click').click();
		}

        function update_rsvp(result, element) {
            element.parent().html(result.msg);
            window.location.reload();
        }


        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        // append latest sending comment for recruiter messaging
        function add_note(result, element) {
           /*
           var commentContainer = element.parent().prev();
            if(commentContainer.hasClass('hide')){
                commentContainer.removeClass('hide')
            }
            commentContainer.prepend(result.msg);
            */
            if($('#display_add_notes_rec_msg').hasClass('hide')){
                $('#display_add_notes_rec_msg').removeClass('hide')
            }
            $('#display_add_notes_rec_msg').prepend(result.msg);

        }

        // append latest sending comment for interview notes
        function add_note_interview(result, element) {
            if($('#display_add_notes_intrvw_note ').hasClass('hide')){
                $('#display_add_notes_intrvw_note ').removeClass('hide')
            }
            $('#display_add_notes_intrvw_note ').prepend(result.msg);
        }

        function send_loader() {
            $(".send-loader").css('display','block');
        }

        // append candidate messaging
        function add_note_candidate(result, element) {
            if($('#display_candidate_rec_msg').hasClass('hide')){
                $('#display_candidate_rec_msg').removeClass('hide')
            }

            $(".send-loader").css('display','none');
            //$('#update_conversation').val(result.update_conversation);
            $('#display_candidate_rec_msg').prepend(result.msg);
            if (result.message_template != '') {
                $('#insert_template').append('<option value="'+result.message_template.id+'">'+result.message_template.template_name+'</option>');
            }
            CKEDITOR.instances['last_conversation'].setData('');
            $(".form-temp").css('display','none');

        }

        // Read all message from inbox and sent and update db
        $('#send_mail_to_candidate').on('click', function() {
            $('#read_candidate_reply').click();
        });

    
        function getothersval(option) {
          if(option.value=='other')
          {
            $('#other_input_field').empty();
            $('#other_input_field').append('<br><input type="text" name="other_text" class="form-control" placeholder="Please Enter Text For Other" required="required">');
          } else {
            $('#other_input_field').empty();
          }
        }

        document.getElementById("label-resume").style.paddingRight = "0px";
        document.getElementById("resume").style.width = "50%";


        function select_candidate_messaging_tab()
        {
            $('#candidateProfileTabs a[href="#candidate_messaging"]').tab('show');
        }

        function addSubject() {
            if($("#save_template").prop('checked') == true){
                $(".form-temp").css('display','block');
            }else{
                $(".form-temp").val('');
                $(".form-temp").css('display','none');
            }
        }

        function getMessageTemplate() {
            var template_id = $('#insert_template').val();
            $.ajax({
                type:'post',
                url:'<?php echo base_url('messages/get_message_template') ?>',
                data:{id:template_id},
                success:function(result){
                    var obj = JSON.parse(result);
                    $("#subject").val(obj.subject);
                    CKEDITOR.instances['last_conversation'].setData(obj.template);
                    }
                });
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

    <script type="text/javascript">
        $(window).load(function() {
            var url = window.location.href;
            var str2 = "email";
            if(url.indexOf(str2) != -1){
                $('#send_mail_to_candidate').click();
            }
        });
    </script>

@endsection