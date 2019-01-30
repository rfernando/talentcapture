@extends('admin.template._main')

@section('title','Job Details')@endsection


@section('page-css')
        <!-- Select2 -->
<link rel="stylesheet" href="{{ admin_assets_url('plugins/select2/select2.min.css') }}">
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
                                    <li {{ (isset($candidate->id) && $candidate->id==$c->id) ? 'class="active"': '' }}>
                                        <a href="{{ admin_url('jobs/candidate_detail/'.$job->id.'/'.$c->id) }}" >
                                            <i class="fa fa-user"></i> {{ $c->name }}
                                            @if($c->hired)
                                                <i class="fa fa-check-circle pull-right text-success" data-toggle="tooltip" title="Candidate Hired"></i>
                                            @elseif($c->client_accepted)
                                                <i class="fa fa-{{ ($c->client_accepted == 1) ? 'check text-success' : 'times-circle text-danger' }} pull-right" data-toggle="tooltip" title="Candidate {{ ($c->client_accepted == 1) ? 'Accepted' : 'Rejected' }}"></i>
                                            @else
                                                <i class="fa fa-exclamation-circle pull-right text-warning" data-toggle="tooltip" title="Pending Approval"></i>
                                            @endif
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
                       <form action="{{ admin_url('jobs/assign_agency/'.$job->id) }}" method="post" class="form-horizontal validateForm">
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
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-light-blue-active">
                        <h3 class="widget-user-username">
                            {{ $agency->first_name." ".$agency->last_name }}
                            <span class="pull-right" style="font-size: 0.5em">
                <input type="text" class="star-rating" data-size="xs" value="{{ $avgRating }}">
            </span>
                        </h3>
                        <h5 class="widget-user-desc">{{($agency->user_profile->role != 'Other' ) ? $agency->user_profile->role.' @ ' : '' }} {{ $agency->user_profile->company_name }}</h5>
                        {{--@if(get_user('type') == 'agency')
                            @if($isFavourite)
                                <a href="{{ base_url('agency/remove_favourite/'.$agency->id) }}" class="widget-user-contact-link pull-left"><i class="fa fa-thumbs-o-down"></i> Remove from Preferred</a>
                            @else
                                <a href="{{ base_url('agency/add_favourite/'.$agency->id) }}" class="widget-user-contact-link pull-left"><i class="fa fa-thumbs-o-up"></i> Add to Preferred</a>
                            @endif
                        @endif --}}
                        {{--<a href="{{ base_url('messages/chat/'.$agency->id) }}"  class="widget-user-contact-link pull-right"><i class="fa fa-envelope"></i> Send a Message</a>--}}
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{ ($agency->profile_pic) ? base_url('public/uploads/'.$agency->profile_pic) :  base_url('public/img/default_profile_pic.jpg') }}" alt="User Avatar">
                    </div>
                    {{--<div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>--}}
                    <div class="box-footer">
                        {{--<div class="row">
                            <div class="col-xs-6">
                                <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                                <p class="text-muted">
                                    {{ $agency->email }}
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <span>
                                    <a href="{{$agency->user_profile->linkedin_url}}" target="_blank"><i class="fa fa-linkedin-square fa-2x margin-r-10"></i></a>
                                </span>


                                    @if($agency->user_profile->facebook_url != '')
                                        <span>
                                            <a href="{{$agency->user_profile->facebook_url}}" target="_blank"><i class="fa fa-facebook-square fa-2x margin-r-10"></i></a>
                                        </span>
                                    @endif

                                    @if($agency->user_profile->twitter_url != '')
                                        <span>
                                            <a href="{{$agency->user_profile->twitter_url}}" target="_blank"><i class="fa fa-twitter-square fa-2x margin-r-10"></i></a>
                                        </span>
                                    @endif



                            </div>
                        </div>--}}
                        <div class="row">
                            <div class="col-xs-6">
                                <strong><i class="fa fa-globe margin-r-5"></i> Website</strong>
                                <p class="text-muted ">
                                    <a href="{{ $agency->user_profile->company_website_url }}">
                                        {{ $agency->user_profile->company_website_url }}
                                    </a>
                                </p>
                            </div>
                            @unless(!$agency->user_profile->company_address)
                                <div class="col-xs-6 text-right">
                                    <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                                    <address>
                                        <strong>{{ $agency->user_profile->company_name }}</strong>
                                        <br>{{ $agency->user_profile->company_address }}
                                        <br>{{ $agency->user_profile->city }},
                                        {{ ($agency->user_profile->state()->first() != '') ? $agency->user_profile->state()->first()->abbreviation : ''}} {{ $agency->user_profile->zipcode }}
                                        <br>
                                        {{--<abbr title="Phone">P:</abbr>(123) 456-7890--}}
                                    </address>
                                </div>
                            @endunless

                        </div>
                        <hr>

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#description" data-toggle="tab"><i class="fa fa-info-circle"></i> Description</a></li>
                                <li><a href="#review" data-toggle="tab"><i class="fa fa-star"></i> Reviews</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="description">
                                    <p>{{ $agency->user_profile->company_desc }}</p>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="review">
                                    @if(count($agency->agency_ratings))
                                        <div class="box-footer box-comments">
                                            @each('partials._review', $agency->agency_ratings, 'ratings')
                                        </div>
                                    @else
                                        <p>No new notes</p>
                                    @endif
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection
@section('page-js')
    <script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>
@endsection

@section('end-script')
    <script  type="text/javascript">
		$('.select2').select2({
            width: '100%',
            placeholder : 'Select Agency'
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
        <script type="application/javascript">
                $('document').ready(function(){

                    $(".star-rating").rating({
                        "size":"xs",
                        "clearButton" : '',
                        "showCaption" : false,
                        "readonly" : true
                    });
                });

    </script>
@endsection