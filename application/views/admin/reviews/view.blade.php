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
            <div class="col-md-4">

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
            <div class="col-md-3">
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
                @include('jobs._accepted_agencies')
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
                                    </span>
                                </div>
                            </div>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#info" data-toggle="tab"><i class="fa fa-info-circle"></i> Info</a></li>
                                    <li><a href="#attachments" data-toggle="tab"><i class="fa fa-paperclip"></i> Attachments</a></li>
                                    <li><a href="#recruiter-notes" data-toggle="tab"><i class="fa fa-"></i> Interview Notes</a></li>
                                    <li><a href="#employer-feedback" data-toggle="tab"><i class="fa fa-comments"></i> Employer Feedback</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="active tab-pane" id="info">
                                        <dl>

                                            <dt>Name</dt>
                                            <dd>{{ $candidate->name }}</dd>

                                            <dt>Candidate Summary</dt>
                                            <dd>{{ $candidate->employment_history; }}</dd>

                                            <dt>Notes</dt>
                                            <dd>{{ $candidate->employment_history; }}</dd>
                                        </dl>
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="attachments">
                                        <ul class="list-group list-inline">
                                            <li class="list-group-item">
                                                <a href="#"><i class="fa fa-file-pdf-o fa-3x"></i> </a><br>
                                                <span class="text-info">Resume</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane" id="recruiter-notes">
                                        <p>No Interview Notes</p>
                                    </div>

                                    <div class="tab-pane" id="employer-feedback">
                                        <p>No Employer Feedback</p>
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
@endsection