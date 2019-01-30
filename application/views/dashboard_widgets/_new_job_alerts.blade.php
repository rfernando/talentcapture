<div id="rsvp-result-container" style="display: none;"></div>
@if($avgRating == 0 || $avgRating > 3)
    @if($newJobsAlert->count())
        <ul class="nav nav-pills nav-stacked {{ (count($newJobsAlert) > 5) ? 'slimScroll' : '' }}">
            @foreach($newJobsAlert as $job)
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
                            <div class="pull-right" style="margin-top: 10px;">
                                <a href="{{ base_url('agency/update_job_response/accepted/'.$job->id) }}" class="btn btn-sm btn-success ajax" data-success="update_candidate_rsvp" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" title="Accept Job"><i class="fa fa-check"></i></a>
                                <a href="{{ base_url('agency/update_job_response/rejected/'.$job->id) }}" class="btn btn-sm btn-danger ajax" data-success="update_candidate_rsvp" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-toggle="tooltip" title="Reject Job"><i class="fa fa-close"></i></a>
                            </div>
                            <a href="{{ base_url('searches/job_detail/'.$job->id) }}" style="width: 75%;" data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion" href="#my-job-{{ $job->id }}">
                                <strong>{{ $job->title }}</strong><br>
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
                            </a>
                        </li>
                        @else
                        <li>
                            <div id="popover-{{ $job->id }}" class="hide">
                                @include('agency._job_details',['popover' => true])
                            </div>
                            
                            <a style="width: 100%;" data-content_id="popover-{{ $job->id }}" rel="popover" data-html="true" data-placement="left" data-parent="#accordion" href="#my-job-{{ $job->id }}">
                                <strong>{{ $job->title }}</strong><br>
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
                            </a>
                        </li>
                        @endif
                        @endif
                    @endif
                @endif
            @endforeach
        </ul>
    @else
        <p>There are no new TalentGrams to review at this time. Open TalentGrams you have previously accepted are listed below.</p>
    @endif
@else
    {{--<p>You will not be shown any search opportunities as your average rating is {{ $avgRating }} which is less than 3 after {{ $no_of_reviews }} reviews.</p>--}}
@endif
    