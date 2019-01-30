@section('page-css')
    @parent
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/datepicker/datepicker3.css') }}">
@endsection


<div class="modal fade" tabindex="-1" role="dialog" id="candidate_payment_request" style="font-size: 14px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{ ($candidate->hired) ? 'New Hire Details' : 'Report Candidate as Hired' }}</h4>
            </div>
            <form action="{{ base_url('searches/request_payment/'.$job->id) }}" method="post" id="requestPaymentForm">

            <div class="modal-body">
                   
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
                            <h5>Details Added by : {{ $hire_detail->added_by()->first()->first_name.' '.$hire_detail->added_by()->first()->last_name}} on 
                                {{ date('d M, Y', strtotime($hire_detail->created_at)) }}
                                <!-- {{ date('F j, Y', strtotime($hire_detail->created_at)) }} --></h5>
                            <dl class="pull-right">
                                <dt><i class="fa fa-dollar"></i> Base Salary</dt>
                                <dd>{{ (str_replace(",","",$hire_detail->base_salary)) }}</dd>
                            </dl>
                            <dl>
                                <dt><i class="fa fa-calendar"></i> Start Date</dt>
                                <dd>
                                    {{ date('d M, Y', strtotime($hire_detail->start_date)) }}
                                    <!-- {{ date('F j, Y', strtotime($hire_detail->start_date)) }} -->
                                </dd>
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
                        @include('jobs._hired_candidates', ['hired_candidate' => $candidate])
                    </ul>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Submit"> {{--data-form="#requestPaymentForm"--}}
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="send_email">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('dashboard_widgets._quick_email')
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="box box-primary" id="candi_box">
    {{--@if($candidate->resume)
        <div class="modal fade" id="resumeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <iframe src="{{ base_url('public/uploads/docs/'.$candidate->resume) }}" width="100%" height="500"></iframe>
                    </div>
                </div>
            </div>
        </div>
    @endif--}}

    <div class="box-header with-border">
        <h3 class="box-title"><?php /*?>{{ $candidate->name }}<?php */?></h3>
        <!-- /.box-tools -->
        <div class="box-tools pull-right">
            <div class="has-feedback">
                @if($candidate->hired)
                 <label class="label label-success">Candidate Hired</label>
                @elseif($candidate->client_accepted)
                    <label class="label label-{{ ($candidate->client_accepted == 1) ? 'success' : 'danger' }}" >Candidate {{ ($candidate->client_accepted == 1) ? 'Accepted' : 'Rejected' }}</label>
                @else
                    <label class="label label-success">Active</label>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {{ flash_msg() }}
        <div class="post">
            <div class="user-block">
                <img class="img-circle img-bordered-sm" src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="User Image">
                <span class="usernamenew">
                    <a href="javascript:void(0)">{{ $candidate->name }}&nbsp;&nbsp;&nbsp;&nbsp;</a> 
                    @if($candidate->linkedin_url != '')
                        <span>
                            <a href="{{($candidate->client_accepted == 0 && $job->user_id==get_user('id')) ? 'javascript:void(0)'  : $candidate->linkedin_url }}" target="_blank"><i class="fa fa-linkedin-square fa-lg margin-r-5"></i></a>
                        </span>
                    @endif

                    @if($candidate->facebook_url != '')
                        <span>
                            <a href="{{($candidate->client_accepted == 0 && $job->user_id==get_user('id')) ? 'javascript:void(0)':$candidate->facebook_url }}" target="_blank"><i class="fa fa-facebook-square  fa-lg margin-r-5"></i></a>
                        </span>
                    @endif
                    <span class="pull-right">
                        @if($candidate->hired)
                            <a href="#" data-toggle="modal" data-target="#candidate_payment_request" rel='tooltip' title="Review Hire Details" class="pull-right btn-box-tool {{ ($hasHireDetails) ? 'hired-candidate ' : 'hire-candidate' }}"><i class="fa fa-handshake-o fa-2x text-success"></i></a> &nbsp;&nbsp;
                            <a href="{{ base_url('messages/chat/'.$job->user_id.'/'.$candidate->id.'/'.$job->id)}}" rel="tooltip" title="Send a Message to Employer" class="pull-right btn-box-tool send-message"><i class="fa fa-envelope fa-2x"></i></a>
                        @elseif($hasHireDetails)
                            <label class="pull-right text-success" data-toggle="tooltip" title="Reported Hired">
                                <i class="fa fa-handshake-o fa-lg" style="color: #337ab7; margin-top: 9px;"></i>
                            </label>
                            <a href="{{ base_url('messages/chat/'.$job->user_id.'/'.$candidate->id.'/'.$job->id.'/'.$job->id)}}" rel="tooltip" title="Send a Message to Employer" class="pull-right btn-box-tool send-message"><i class="fa fa-envelope fa-2x"></i></a>
                        @elseif($candidate->client_accepted)
                            @if($candidate->client_accepted == 1)
                                <a href="#" data-toggle="modal" data-target="#candidate_payment_request" rel='tooltip' title="Report Candidate as Hired" class="pull-right btn-box-tool hired-candidate"><i class="fa fa-handshake-o fa-2x" style="color: #97a0b3"></i></a>
                                <a href="{{ base_url('messages/chat/'.$job->user_id.'/'.$candidate->id.'/'.$job->id)}}" rel="tooltip" title="Send a Message to Employer" class="pull-right btn-box-tool send-message"><i class="fa fa-envelope fa-2x"></i></a>
                            @endif
                            
                        @else
                            <label data-toggle="tooltip" title="Waiting for Employer Response">
                                <i class="fa fa-exclamation-circle fa-lg pull-right text-warning" data-toggle="tooltip"></i>
                            </label>
                        @endif
                    </span>
                </span>
                <?php /*
                <span class="description"><i class="fa fa-building margin-r-5"></i>
                    <a href="">{{ $candidate->agency()->first()->user_profile()->first()->company_name }}</a>
                </span>
                */?>
                <span class="description pull-right"><i class="fa fa-calendar margin-r-5"></i>
                    {{ date('d M, Y', strtotime($candidate->updated_at))}}
                </span>
            </div>
        </div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs"> <!-- fixing candidate profile tab to fix size -->
                <li class="active" style="width: 16.5%"><a href="#info" data-toggle="tab"><i class="fa fa-info-circle fa-2x" data-toggle="tooltip" title data-original-title="Info"></i></a></li>
                <li style="width: 16.5%"><a href="#recruiter-notes" data-toggle="tab"><i class="fa fa-file fa-2x" data-toggle="tooltip" title data-original-title="Interview Notes"></i></a></li>
                <li style="width: 16.5%"><a href="#attachments" data-toggle="tab"><i class="fa fa-paperclip fa-2x" data-toggle="tooltip" title data-original-title="Documents"></i></a></li>
                <li style="width: 16.5%"> <!-- adding candidate messaging tab for Agency user for RP-801 -->
                    <a href="#candidate_messaging" data-toggle="tab" id="send_mail_to_candidate"><i class="fa fa-envelope fa-2x" data-toggle="tooltip" title data-original-title="Candidate Messaging"></i> 
                    </a>
                </li>

                <li style="width: 16.5%">
                    <a href="#candidate-feedback" data-toggle="tab" id="discuss_click"><i class="fa fa-comments fa-2x" data-toggle="tooltip" title data-original-title="<?php 
                            // Chanage Candidate Discussion to Recruiter Messaging/Employer Messaging using given condition  
                            $candidate_owner = User::where(['id' => $candidate->user_id])->lists('type')['0'];
                            $job_owner       = User::where(['id' => $job->user_id])->lists('type')['0'];
                            if($candidate_owner=='agency' && $job_owner=='employer') { echo "Employer Messaging"; }
                            if($candidate_owner=='agency' && $job_owner=='agency') { echo "Recruiter Messaging"; }
                        ?>"></i> 
                        
                        <!-- <?php 
                            // Chanage Candidate Discussion to Recruiter Messaging/Employer Messaging using given condition  
                            $candidate_owner = User::where(['id' => $candidate->user_id])->lists('type')['0'];
                            $job_owner       = User::where(['id' => $job->user_id])->lists('type')['0'];
                            if($candidate_owner=='agency' && $job_owner=='employer') { echo "Employer Messaging"; }
                            if($candidate_owner=='agency' && $job_owner=='agency') { echo "Recruiter Messaging"; }
                        ?> -->
                    </a>
                </li>
                <li style="width: 16.5%"><a href="#activity-timeline" data-toggle="tab"><i class="fa fa fa-clock-o fa-2x"  data-toggle="tooltip" title data-original-title="Activity Timeline"></i></a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="info">
                    <div class="row">
                        <div class="col-md-3 text-left">
                            <strong><i class="fa fa-phone margin-r-5"></i> Phone </strong>
                            @if($candidate->phone != '')
                                <!-- <p>{{ $candidate->phone; }}</p> -->
                                <p class="text-muted"><a href="tel:{{ $candidate->phone; }}">{{ $candidate->phone; }}</a></p>
                            @else
                                <p class="text-muted">Confidential</p>
                            @endif
                        </div>

                        <div class="col-md-3 text-left">
                            <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                            @if($candidate->email != '')
                                <p class="text-muted"><a href="#" id="user_email" data-toggle="modal" data-target="#send_email" data-mailto="{{ $candidate->email; }}">{{ $candidate->email; }}</a></p>
                            @else
                                <p class="text-muted">Confidential</p>
                            @endif
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
                        @if($candidate->salary)
                        <div class="col-md-3 text-left">
                                <strong><i class="fa fa-dollar margin-r-5"></i> Desired Salary</strong>
                                <p class="text-muted">{{ $candidate->salary; }}</p>
                        </div>
                        @endif
                        @if($candidate->residency)
                            <div class="col-md-3 text-left">
                                    <strong><i class="fa fa-home margin-r-5"></i> Residency</strong>
                                    <p class="text-muted">{{ $candidate->residency; }}</p>
                            </div>
                        @endif
                    </div>
                    <hr>
                    <dl>
                        <dt><i class="fa fa-history margin-r-5"></i> Candidate Summary <a href="#" data-toggle="modal" data-target="#candidate_employeement_history_edit" rel='tooltip' title="Edit Candidate Summary"><i class="fa fa-pencil"></i></a> </dt>


                        <dd>   
                            <iframe src="<?php echo base_url().'jobs/employmenthistory_pdf/'.$candidate->id; ?>"   width="100%" style="height:40em" > </iframe>
                        </dd>

                        <?php /*?><dt>Notes</dt>
                        <dd>{{ $candidate->notes; }}</dd><?php */?>
                    </dl>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="candidate_employeement_history_edit">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Edit Candidate Summary</h4>
                            </div>
                            <form action="{{ base_url('searches/update_candidate_employment_history/'.$job->id.'/'.$candidate->id.'/'.$jobOwner_type.'/'.$jobOwner_type_closed) }}" method="post" id="requestPaymentForm">

                            <div class="modal-body">
                                
                                    <textarea name="candidates-employment_history" id="candidates-employment_history">{{$candidate->employment_history}}</textarea>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" value="Submit"> {{--data-form="#requestPaymentForm"--}}
                            </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="tab-pane" id="attachments">
                    <ul class="list-group list-inline">
                        @if($candidate->resume)

                        <li class="list-group-item">
                            <a href="javascript:void(0)"  iframe-data="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidate->resume) }}&embedded=true" download-data="{{ base_url('public/uploads/docs/'.$candidate->resume) }}" class="files_btn">
                                <!-- <i class="fa fa-file-pdf-o fa-3x"></i> -->
                                <small class="text-info">{{$candidate->resume}}</small>
                            </a>
                            
                            <a href="{{ base_url('searches/delete_candidate_resume/'.$job->id.'/'.$candidate->id.'/'.$candidate->resume) }}">
                                <i class="fa fa-trash pull-right text-warning delete-taltnt-resume" data-toggle="tooltip" title="Delete"></i>
                            </a>
                        </li>
                        @endif

                        <?php $candidatesdocuments = Candidate_documents::where(['candidate_id' => $candidate->id])->get(); ?>
                        @foreach ($candidatesdocuments as $candidatesdocument)
                        <li class="list-group-item">
                        
                            <a href="javascript:void(0)" iframe-data="https://docs.google.com/viewer?url={{ base_url('public/uploads/docs/'.$candidatesdocument->title) }}&embedded=true" download-data="{{ base_url('public/uploads/docs/'.$candidatesdocument->title) }}" class="files_btn">                          
                            <small class="text-info">{{$candidatesdocument->title}}</small>
                            </a>
                            <a href="{{ base_url('searches/delete_resume/'.$job->id.'/'.$candidate->id.'/'.$jobOwner_type.'/'.$jobOwner_type_closed.'/'.$candidatesdocument->id) }}"><i class="fa fa-trash pull-right text-warning delete-taltnt-resume" data-toggle="tooltip" title="Delete"></i></a>
                            <br>
                        </li>
                        
                        @endforeach

                        <div class="box-body">
                            <form action="{{ base_url('searches/upload_attachment/'.$job->id.'/'.$candidate->id.'/'.$jobOwner_type.'/'.$jobOwner_type_closed) }}" method="post" class="form-horizontal validateForm" enctype="multipart/form-data">
                                {{ generate_form_fields($candidateuploadFields, 2) }}
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-9">
                                        <button type="submit" class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>  
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

                <!-- /.tab-pane -->
               
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
                    @if($candidate->client_accepted != '0')
                    <div class="box-footer">
                            <form action="{{ base_url('jobs/add_feedback/'.$candidate->id) }}" class="ajax" data-success="add_note" method="post">
                            @if($userImg = get_user('profile_pic'))
                                <img class="img-responsive img-circle img-sm" src="{{ base_url('public/uploads/'.$userImg) }}" alt="Alt Text">
                            @else
                                <img class="img-responsive img-circle img-sm" src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="Alt Text">
                            @endif
                            <div class="img-push">

                                @if($job_owner=='employer')
                                    <?php
                                    $messaging_placeholder = Site_messages::where('type','=','employer_messaging_placeholder')->first();
                                    ?>
                                @else
                                    <?php
                                    $messaging_placeholder = Site_messages::where('type','=','recruiter_messaging_placeholder')->first();
                                    ?>
                                @endif
                                <!-- <input type="hidden" name="job_provider"  id="job_provider" value="<?=$candidate->agency()->first()->id;?>"> -->
                                <input type="hidden" name="job_provider"  id="job_provider" value="<?=$job->user_id;?>">
                                <input type="hidden" name="job_id"  id="job_id" value="<?=$job->id;?>"">
                                
                                <textarea onKeyUp="discussion_submit_validate()" rows="6" name="employer_feedback" id="employer_feedback" class="form-control input-sm" placeholder="<?php echo $messaging_placeholder->msg; ?>" autocomplete="off"></textarea><br>
                                <input id="discussion_submit" disabled class="btn btn-primary" type="submit" value="Send">
                            </div>
                        </form>
                    </div>
                    @else
                    <p class="text-muted">The candidate must be accepted first before you can view messaging system</p>
                    @endif
                    <div class="box-footer box-comments {{ ($hasFeedback = $candidate->feedback()->count()) ? '' : 'hide'  }}" id="display_add_notes_rec_msg">
                        @if($hasFeedback)
                            @each('partials._note', $candidate->feedback()->with('added_by')->orderBy('updated_at', 'desc')->get(), 'note')
                        @else
            
                        @endif
                    </div>
                    
                </div>

                 <!-- new section: candidate messaging for RP-801 -->
                <div class="tab-pane" id="candidate_messaging">
                    @if($conversation_data->count())
                    <div class="box-footer box-comments {{ ($hasComments1 = $conversation_data->count()) ? '' : 'hide' }} agency_candidate_messaging" id="display_candidate_rec_msg">
                     @if($hasComments1)
                            @each('partials._coversation',$conversation_data, 'coversation')
                    @endif
                    </div>
                    @else

                    <?php
                        $candidate_messaging_placeholder = Site_messages::where('type','=','candidate_messaging_recruiter_placeholder')->first();
                    ?>

                    <div class="text-muted"><?php echo $candidate_messaging_placeholder->msg; ?></div>
                    @endif
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
<!-- /. box -->



@section('page-js')
    @parent
    <!-- Datepicker -->
    <script src="{{ admin_assets_url('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
   <!-- <script src="{{ admin_assets_url('plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
     CK Editor -->
    <!-- <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>-->
    <script src="{{ admin_assets_url('plugins/ckeditor/ckeditor.js') }}"></script>
@endsection



@section('end-script')
    @parent

   
   <script type="text/javascript">

        var config = {};

        config.extraPlugins='confighelper';

        $(function () {
            CKEDITOR.replace('candidates-employment_history',config);
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

        $(document).ready(function()
        {

        });

       

    </script>
   
    <script type="application/javascript">

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        function update_rsvp(result, element) {
            element.parent().html(result.msg);
        }


        // append latest sending comment for recruiter/employer messaging
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

        function discussion_click()
        {
            $('#discuss_click').click();
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