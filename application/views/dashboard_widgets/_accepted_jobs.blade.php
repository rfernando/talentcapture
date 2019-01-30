    <div class="box-group acceptedjob-body" id="accordion_employer">
       <!--  <div class="panel box"  style="overflow-y: auto; max-height: 400px;"> -->
            <div class="box-header with-border ">
                <h4 class="box-title box-title-padd" style="width: 100%;">
                    <ul class="nav nav-tabs">
                    <li {{ ($type_check == 'employer') ? 'class="active"' : '' }} style="width: 50%;"><a data-toggle="tab" data-html="true" href="#employer_job_list" data-parent="#accordion_employer" onclick="BoldText(this);">
                        Employer
                    </a></li>
                    <li {{ ($type_check == 'agency') ? 'class="active"' : '' }} style="width: 50%;"><a data-toggle="tab" data-html="true" href="#agency_job_list" data-parent="#accordion_employer" onclick="BoldText(this);">
                        Agency
                    </a></li>
                    </ul>
                </h4>
            </div>
            <div class="panel box"  style="overflow-y: auto; max-height: 400px;">
            <div class="tab-content">
            <div class="tab-pane fade {{ ($type_check == 'employer') ? 'active in' : '' }}" id="employer_job_list">
                <ul class="nav nav-pills nav-stacked {{ (count($newJobsAlert) > 5) ? 'slimScroll' : '' }}">
                   
                    @foreach($newJobsAlert as $job)

                    @if($job->user($job->user_id)->first()->type == 'employer')

                        <?php
                            //Check prefered agency
                            $show_preffered_job = TRUE;
                            if($job->notify_preferred){
                                if(!job_preferred_agencies::where('agency_id',get_user('id'))->where('job_id',$job->id)->get()->count()){
                                    $show_preffered_job = FALSE;
                                }
                            }
                        ?>

                        @if($show_preffered_job)
                            @if(in_array($job->user->type,$job_user_type))
                                @if(!in_array($job->user_id, $rejected_rater))
                                <li>
                                    <div id="popover-{{ $job->id }}" class="hide">
                                        @include('agency._job_details',['popover' => true])
                                    </div>
                                    <div class="pull-right" style="margin-top: 15px;">
                                        <a href="{{ base_url('agency/update_job_response/accepted/'.$job->id) }}" class="btn btn-sm btn-success ajax" data-success="update_candidate_rsvp" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" data-placement="left" title="I’m Interested"><i class="fa fa-thumbs-up"></i></a>
                                        <a href="{{ base_url('agency/update_job_response/rejected/'.$job->id) }}" class="btn btn-sm btn-danger ajax" data-success="update_candidate_rsvp" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" data-placement="left" title="Not Interested"><i class="fa fa-thumbs-down"></i></a>
                                    </div>
                                   
                                    <a href="{{ base_url('searches/job_detail/'.$job->id.'/emp/emp') }}" style="width: 75%;" data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion_employer" href="#my-job-{{ $job->id }}">

                                        @if($profileImg = $job->user->profile_pic)
                                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                                        @else
                                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                                        @endif

                                        <div class="contacts-list-info">
                                            <span style="color:#2e749c"><strong>{{ $job->title }}</strong></span><br>
                                            @if($job->user->type == "agency")
                                                @if($job->client_name_confidential)
                                                    <small class="text-muted">{{ $job->user->user_profile()->first()->company_name  }} </small>
                                                @else
                                                    <small class="text-muted">{{ $job->client_name }}</small>
                                                @endif
                                            @elseif($job->user->type == "employer")
                                                <small class="text-muted">{{ $job->user->user_profile()->first()->company_name }}</small>
                                            @endif
                                            {{--@unless($job->client_name_confidential)
                                                <small class="text-muted">{{ $job->client_name }}</small>
                                            @endunless--}}
                                        </div>                
                                    </a>
                                </li>
                                @endif
                            @endif
                        @endif
                        @endif
                    @endforeach
                </ul>

                <ul class="nav nav-pills nav-stacked {{ (count($acceptedJobs) > 5) ? 'slimScroll' : '' }}">
                    @foreach($acceptedJobs->orderBy('updated_at', 'desc')->get() as $job)
                       <?php $checkstatus = Accepted_job::where('user_id','=',get_user('id'))->where('job_id','=',$job->id)->whereBetween('estatus',[0, 2])->first(); ?>
                        @if($checkstatus)
                        @if($job->user($job->user_id)->first()->type == 'employer')
                        @if($subscription['dstatus'] == 'active')
                            <li>
                               
                               <?php
										$today_dt = time();
										$acc_dt = strtotime($checkstatus->created_at);
										$datediff = $today_dt - $acc_dt;
										$diffToChk = ($datediff / (60 * 60 * 24));
									
									if($checkstatus->estatus == 0 && $diffToChk>=10)
									{
										
			
										?>
                
                                   
                                   <div class="pull-right" style="margin-top: 15px;">
                                       
                                       
                                        <a href="{{ base_url('searches/close_dash/'.$job->id.'/-1') }}" class="btn btn-sm btn-danger ajax" data-success="update_candidate_rsvp2" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" data-placement="left" title="Archive" ><i class="fa fa-lock"></i></a>

                                    </div>
                                   
                                   
                                   <?php
									}
									
									?>
                               
                                <div class="pull-right" style="margin-top: 15px;">
                                    <?php $checkstatus = Accepted_job::where('user_id','=',get_user('id'))->where('job_id','=',$job->id)->whereBetween('estatus',[0, 2])->first(); ?>
                                    @if($checkstatus)
                                    @if($checkstatus->estatus == 1)
                                        <label class="label margin-r-5 label-success">
                                            Approved
                                        </label>
                                    @elseif($checkstatus->estatus == 0)
                                        <label class="label margin-r-5 label-warning">
                                            Waiting for approval
                                        </label> 
                                    @elseif($checkstatus->estatus == 2)
                                        <label class="label margin-r-5 label-danger">
                                            Declined
                                        </label> 
                                           <?php
									$checkstatus->estatus = 3;
									$checkstatus->save();
									?>  
                                    @endif
                                    @endif
                                </div>
                                <div id="popover-{{ $job->id }}" class="hide">
                                    @include('agency._job_details',['popover' => true])
                                </div>
                                <a href="{{ base_url('searches/job_detail/'.$job->id.'/emp/emp') }}" style="width: 85%" data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion_employer" href="#my-job-{{ $job->id }}">
                                    
                                    @if($profileImg = $job->user->profile_pic)
                                        <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                                    @else
                                        <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                                    @endif

                                    <div class="contacts-list-info">
                                        <span style="color:#2e749c"><strong>{{ $job->title }}</strong></span><br>
                                        <small class="text-muted">{{ $job->user->user_profile()->first()->company_name  }} </small>
                                    </div>                
                                </a>
                            </li>
                        @else
                            <li>
                               <?php
										$today_dt = time();
										$acc_dt = strtotime($checkstatus->created_at);
										$datediff = $today_dt - $acc_dt;
										$diffToChk = ($datediff / (60 * 60 * 24));
									
									if($checkstatus->estatus == 0 && $diffToChk>=10)
									{
										
			
										?>
                
                                   
                                   <div class="pull-right" style="margin-top: 15px;">
                                       
                                       
                                        <a href="{{ base_url('searches/close_dash/'.$job->id.'/-1') }}" class="btn btn-sm btn-danger ajax" data-success="update_candidate_rsvp2" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" data-placement="left" title="Archive"><i class="fa fa-lock"></i></a>

                                    </div>
                                   
                                   
                                   <?php
									}
									
									?>
                                <div class="pull-right" style="margin-top: 15px;">
                                    <?php $checkstatus = Accepted_job::where('user_id','=',get_user('id'))->where('job_id','=',$job->id)->whereBetween('estatus',[0, 2])->first(); ?>
                                    @if($checkstatus)
                                    @if($checkstatus->estatus == 1)
                                        <label class="label margin-r-5 label-success">
                                            Approved
                                        </label>
                                    @elseif($checkstatus->estatus == 0)
                                        <label class="label margin-r-5 label-warning">
                                            Waiting for approval
                                        </label> 
                                    @elseif($checkstatus->estatus == 2)
                                        <label class="label margin-r-5 label-danger">
                                            Declined
                                        </label> 
                                        <?php
									$checkstatus->estatus = 3;
									$checkstatus->save();
									?>        
                                    @endif
                                    @endif
                                </div>
                                <div id="popover-{{ $job->id }}" class="hide">
                                    @include('agency._job_details',['popover' => true])
                                </div>
                                <a href="{{ base_url('searches/job_detail/'.$job->id.'/emp/emp') }}"  style="width: 85%" data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion_employer" href="#my-job-{{ $job->id }}">
                                    <strong>{{ $job->title }}</strong><br>
                                    <small class="text-muted">{{ $job->user->user_profile()->first()->company_name  }} </small>
                                </a>

                            </li>
                        @endif
                        @endif
                        @endif
                    @endforeach
                </ul>
            </div>
       <!--  </div> -->

        <!-- <div class="panel box"  style="overflow-y: auto; max-height: 400px;"> -->
            <!-- <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-html="true" href="#agency_job_list" data-parent="#accordion_employer" onclick="BoldText(this);">
                        Agency Hub
                    </a>
                </h4>
            </div> -->
            <div class="tab-pane fade {{ ($type_check == 'agency') ? 'active in' : '' }} tab-pane fade" id="agency_job_list">
                <ul class="nav nav-pills nav-stacked {{ (count($newJobsAlert) > 5) ? 'slimScroll' : '' }}">
                    @foreach($newJobsAlert as $job)
                    @if($job->user($job->user_id)->first()->type == 'agency')

                        <?php
                            //Check prefered agency
                            $show_preffered_job = TRUE;
                            if($job->notify_preferred){
                                if(!job_preferred_agencies::where('agency_id',get_user('id'))->where('job_id',$job->id)->get()->count()){
                                    $show_preffered_job = FALSE;
                                }
                            }
                        ?>
                        @if($show_preffered_job)

                            @if(in_array($job->user->type,$job_user_type))

                                @if(!in_array($job->user_id, $rejected_rater))

                                @if($subscription['dstatus'] == 'active')
                                 
                                <li>
                                    <div id="popover-{{ $job->id }}" class="hide">
                                        @include('agency._job_details',['popover' => true])
                                    </div>
                                    <div class="pull-right" style="margin-top: 15px;">
                                        <a href="{{ base_url('agency/update_job_response/accepted/'.$job->id) }}" class="btn btn-sm btn-success ajax" data-success="update_candidate_rsvp" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" data-placement="left" title="I’m Interested"><i class="fa fa-thumbs-up"></i></a>
                                        <a href="{{ base_url('agency/update_job_response/rejected/'.$job->id) }}" class="btn btn-sm btn-danger ajax" data-success="update_candidate_rsvp" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" data-placement="left" title="Not Interested"><i class="fa fa-thumbs-down"></i></a>
                                    </div>
                                    <a href="{{ base_url('searches/job_detail/'.$job->id.'/agn/agn') }}" style="width: 75%;" data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion_employer" href="#my-job-{{ $job->id }}">
                                        @if($profileImg = $job->user->profile_pic)
                                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                                        @else
                                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                                        @endif

                                        <div class="contacts-list-info">
                                            <span style="color:#2e749c"><strong>{{ $job->title }}</strong></span><br>
                                            @if($job->user->type == "agency")
                                                @if($job->client_name_confidential)
                                                    <small class="text-muted">{{ $job->user->user_profile()->first()->company_name  }} </small>
                                                @else
                                                    <!--display the Agency name whether an agency user lists the client name, or keeps it confidential-->
                                                    <small class="text-muted">{{ $job->user->user_profile()->first()->company_name  }} </small>
                                                @endif
                                            @elseif($job->user->type == "employer")
                                                <small class="text-muted">{{ $job->user->user_profile()->first()->company_name }}</small>
                                            @endif
                                            {{--@unless($job->client_name_confidential)
                                                <small class="text-muted">{{ $job->client_name }}</small>
                                            @endunless--}}
                                        </div>           
                                    </a>
                                </li>
                                @else
                                <li>
                                    {{--<div id="popover-{{ $job->id }}" class="hide">
                                        @include('agency._job_details',['popover' => true])
                                    </div>--}}
                                    
                                    <a style="width: 100%;" {{--data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion_employer" href="#my-job-{{ $job->id }}"--}}>
                                        
                                        @if($profileImg = $job->user->profile_pic)
                                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                                        @else
                                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                                        @endif

                                        <div class="contacts-list-info">
                                            <span style="color:#2e749c"><strong>{{ $job->title }}</strong></span><br>
                                            @if($job->user->type == "agency")
                                                {{--@if($job->client_name_confidential)--}}
                                                    {{--<small class="text-muted">{{ $job->user->user_profile()->first()->company_name  }} </small>--}}
                                                {{--@else--}}
                                                    <small class="text-muted">{{ $job->job_location }}</small>
                                                {{--@endif--}}
                                            @elseif($job->user->type == "employer")
                                                <small class="text-muted">{{ $job->user->user_profile()->first()->company_name }}</small>
                                            @endif
                                            {{--@unless($job->client_name_confidential)
                                                <small class="text-muted">{{ $job->job_location }}</small>
                                            @endunless--}}
                                        </div>        

                                        
                                    </a>
                                </li>
                                @endif
                                @endif
                            @endif
                        @endif
                        @endif
                    @endforeach
                </ul>
                <ul class="nav nav-pills nav-stacked {{ (count($acceptedJobs) > 5) ? 'slimScroll' : '' }}">
                    @foreach($acceptedJobs->orderBy('updated_at', 'desc')->get() as $job)
                       <?php $checkstatus = Accepted_job::where('user_id','=',get_user('id'))->where('job_id','=',$job->id)->whereBetween('estatus',[0, 2])->first(); ?>
                       @if($checkstatus)
                        @if($job->user($job->user_id)->first()->type == 'agency')
                            @if($subscription['dstatus'] == 'active')
                            <li>
                                <div id="popover-{{ $job->id }}" class="hide">
                                    @include('agency._job_details',['popover' => true])
                                </div>
                                
									<?php
										$today_dt = time();
										$acc_dt = strtotime($checkstatus->created_at);
										$datediff = $today_dt - $acc_dt;
										$diffToChk = ($datediff / (60 * 60 * 24));
									
									if($checkstatus->estatus == 0 && $diffToChk>=10)
									{
										
			
										?>
                
                                   
                                   <div class="pull-right" style="margin-top: 15px;">
                                       
                                       
                                        <a href="{{ base_url('searches/close_dash/'.$job->id.'/-1') }}" class="btn btn-sm btn-danger ajax" data-success="update_candidate_rsvp2" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" data-placement="left" title="Archive"><i class="fa fa-lock"></i></a>

                                    </div>
                                   
                                   
                                   <?php
									}
									
									?>
                               
                                <div class="pull-right" style="margin-top: 15px; padding-right: 20px;">
                                    <?php $checkstatus = Accepted_job::where('user_id','=',get_user('id'))->where('job_id','=',$job->id)->whereBetween('estatus',[0, 2])->first(); ?>
                                    @if($checkstatus)
                                    @if($checkstatus->estatus == 1)
                                        <label class="label margin-r-5 label-success">
                                            Approved
                                        </label>
                                    @elseif($checkstatus->estatus == 0)
                                        <label class="label margin-r-5 label-warning">
                                            Waiting for approval
                                        </label> 
                                    @elseif($checkstatus->estatus == 2)
                                        <label class="label margin-r-5 label-danger">
                                            Declined
                                        </label> 
                                    <?php
									$checkstatus->estatus = 3;
									$checkstatus->save();
									?>     
                                    @endif
                                    @endif
                                </div>
                                
                                
                                
                                
                                <a href="{{ base_url('searches/job_detail/'.$job->id.'/agn/agn') }}" style="width: 85%" data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion_employer" href="#my-job-{{ $job->id }}">
                                    @if($profileImg = $job->user->profile_pic)
                                        <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                                    @else
                                        <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                                    @endif

                                    <div class="contacts-list-info">
                                        <span style="color:#2e749c"><strong>{{ $job->title }}</strong></span><br>
                                        @if($job->client_name_confidential)
                                            <small class="text-muted">{{ $job->user->user_profile()->first()->company_name  }} </small>
                                        @else
                                            <!--display the Agency name whether an agency user lists the client name, or keeps it confidential-->
                                            <small class="text-muted">{{ $job->user->user_profile()->first()->company_name  }} </small>
                                        @endif
                                    </div>
                                </a>

                            </li>
                            @else
                            <li>
                                {{--<div id="popover-{{ $job->id }}" class="hide">
                                    @include('agency._job_details',['popover' => true])
                                </div>--}}
                                <a {{--data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion_employer" href="#my-job-{{ $job->id }}"--}}>                                   
                                    @if($profileImg = $job->user->profile_pic)
                                        <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                                    @else
                                        <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                                    @endif

                                    <div class="contacts-list-info">
                                        <span style="color:#2e749c"><strong>{{ $job->title }}</strong></span><br>
                                        <small class="text-muted">{{ $job->job_location }}</small>
                                    </div>

                                </a>

                            </li>
                            @endif
                        @endif
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    </div>

{{--@if($acceptedJobs->count())--}}
{{--@else--}}
{{--<p>There are no accepted TalentGrams to display.</p>--}}
{{--@endif--}}

<script type="text/javascript">
    var currElem = null; //will hold the element that is bold now

 function BoldText(elem) {
 
  if (elem != currElem) { //do thing if you're clicking on a bold link

   if (currElem) //if a link bold right now unbold it

    currElem.style.fontWeight='normal';

   currElem = elem; //assign this element as the current one

   elem.style.fontWeight='bold';  //make the element clicked bold
 
  }

 }
</script>
<style type="text/css">
   .box-title-padd .nav-tabs>li>a {
    padding: 10px 72px;
    }
    .nav-tabs {
    background: #f5f5f5;    
}
.acceptedjob-body .box {
    position: relative;
    border-radius: 3px;
    background: #ffffff;
    border-top: none;
    margin-bottom: 20px;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}
</style>
