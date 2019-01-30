@extends('admin.template._main')

@section('title','Users Details')@endsection


@section('main-content')
    <section class="content-header">
        <h1>
            User Profile
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ admin_url('users') }}"><i class="fa fa-users"></i> Users</a></li>
            <li class="active">{{ $user->first_name.' '.$user->last_name  }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        @if($user->profile_pic != '')
                            <img class="profile-user-img img-responsive img-circle"  src="{{ base_url('public/uploads/'.$user->profile_pic) }}" alt="User profile picture">
                        @else
                            <img class="profile-user-img img-responsive img-circle"  src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="User profile picture" >
                        @endif

                        <h3 class="profile-username text-center">{{ $user->first_name.' '.$user->last_name  }}</h3>

                        <p class="text-muted text-center">{{ ($user_profile != '') ? $user_profile->role : ''}}</p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>User Status</b> <a class="label ajax pull-right {{($user->status)  ? 'label-success' : 'label-default'}}" href="{{admin_url('users/change_status/'.$user->id)}}" data-success="change_status">
                                    {{ ($user->status) ? "Active" : "Inactive"}}
                                </a>
                            </li>
                            @foreach($stats as $key => $val)
                                <li class="list-group-item">
                                    <b>{{ ucwords(str_replace('so','SO\'s',str_replace('_',' ',$key))) }}</b> <a class="pull-right">{{ $val }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ admin_url('users/delete/'.$user->id) }}" onclick="return confirm('Are you sure you want to delete this user ?');" class="btn btn-danger btn-block" disabled><b>Delete Account</b></a>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-4">
                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">User Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                        <p class="text-muted">
                            {{ $user->email }}
                        </p>

                        <hr>

                        <strong><i class="fa fa-phone margin-r-5"></i> Phone</strong>
                        <p class="text-muted">
                            {{ $user->phone }}
                        </p>

                        <hr>

                        <strong><i class="fa fa-user margin-r-5"></i> Account Type</strong>
                        <p class="text-muted">
                            {{ ucfirst($user->type)  }}
                        </p>

                        <hr>

                        {{--<strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

                        <p class="text-muted">Malibu, California</p>

                        <hr>--}}

                        <strong><i class="fa fa-certificate margin-r-5"></i> Industries</strong>

                        <p>
                            @foreach($user->industries()->lists('title') as $industry)
                                <span class="label label-{{ get_random_class() }}">{{ $industry }}</span>
                            @endforeach
                        </p>

                        <hr>

                        <strong><i class="fa fa-black-tie margin-r-5"></i> Profession</strong>

                        <p>
                            @foreach($user->professions()->lists('title') as $profession)
                                <span class="label label-{{ get_random_class() }}">{{ $profession }}</span>
                            @endforeach
                        </p>

                        <hr>

                        @if($user_profile != '' && $user_profile->linkedin_url)
                        <strong><i class="fa fa-linkedin-square fa-2x margin-r-10"></i></strong>


                        <p>
                            <a href="{{ $user_profile->linkedin_url }}">
                                {{ $user_profile->linkedin_url  }}
                            </a>
                        </p>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-4">
                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Company Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if($user_profile != '')
                            <strong><i class="fa fa-building margin-r-5"></i> Name</strong>

                            <p class="text-muted">{{ $user_profile->company_name }}</p>

                            <hr>

                            <strong><i class="fa fa-certificate margin-r-5"></i> Role</strong>

                            <p class="text-muted">{{ $user_profile->role }}</p>

                            <hr>

                            @unless(!$user_profile->company_address)
                            <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                            <address class="text-muted">
                                <strong>{{ $user_profile->company_name }}</strong>
                                <br>{{ $user_profile->company_address }}
                                <br>{{ $user_profile->city }},
                                {{ ($user_profile->state()->first() != '') ? $user_profile->state()->first()->abbreviation : ''}} {{ $user_profile->zipcode }}
                                <br>
                                {{--<abbr title="Phone">P:</abbr>(123) 456-7890--}}
                            </address>

                            <hr>

                            @endunless



                            <strong><i class="fa fa-globe margin-r-5"></i> Website</strong>

                            <p class="text-muted">
                                <a href="{{ $user_profile->company_website_url }}">
                                    {{ $user_profile->company_website_url }}
                                </a>
                            </p>

                            <hr>

                            <strong><i class="fa fa-file-text-o margin-r-5"></i> Descriptions</strong>

                            <p>{{ $user_profile->company_desc }}</p>
                        @else
                            <p>User has not Updated Complete Profile Information yet</p>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            
            
            <div class="col-md-11">
                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Performance Summary</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    
                    
                    
                    <div class="box-group" id="accordion_agency">
                        @if($user->type=='agency')
                        
                            <div class="panel box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-html="true" href="#my_plc_list" data-parent="#accordion_agency">
                                            Placements
                                        </a>
                                    </h4>
                                </div>
                                <div class="box-body panel-collapse collapse" id="my_plc_list">
                                  <table width="100%">
									  <tr><th align="left" colspan="6">At Agency Hub</th></tr>
									  @if(count($agency_placement_info)>0)
									  <tr>
										  <th>Candidate</th>
										  <th width="30%">JOB</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
									  </tr>
									  @else
									  <tr>
										  <th>No Data Yet</th>
										  <th width="30%"></th>
										  <th></th>
										  <th></th>
										  <th></th>
										  <th></th>
									  </tr>
									  @endif
									  @foreach($agency_placement_info as $agency_placement_row)
									  <tr>
										  <td valign="top">{{$agency_placement_row->name}}</td>
										  <td valign="top">{{$agency_placement_row->title}}</td>
										  <td valign="top">{{$agency_placement_row->start_date}}</td>
										  <td valign="top">{{date('Y-m-d',strtotime("+".(int)$agency_placement_row->warranty_period." days", strtotime($agency_placement_row->start_date)))}}</td>
										  <td valign="top">{{"$ ".$agency_placement_row->base_salary}}</td>
										  <td valign="top"><?php
											  //$agency_placement_row->split_percentage = 15.00;
											  echo "$ ".((float)$agency_placement_row->base_salary)*((float)$agency_placement_row->placement_fee/100.00)*((float)$agency_placement_row->split_percentage/100.00);
											  
											  ?></td>
									  </tr>
									  @endforeach
									  @if(count($agency_placement_info)>0)
									  <tr>
										  <th>Candidate</th>
										  <th width="30%">JOB</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
									  </tr>
									  @else
									  <tr>
										  <th></th>
										  <th width="30%"></th>
										  <th></th>
										  <th></th>
										  <th></th>
										  <th></th>
									  </tr>
									  @endif
									  
									  <tr><th align="left" colspan="6"><br>At Employers TalentGrams</th></tr>
									  @if(count($agency_placement_info)>0)
									  <tr>
										  <th>Candidate</th>
										  <th width="30%">JOB</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
									  </tr>
									  @else
									  <tr>
										  <th>No Data Yet</th>
										  <th width="30%"></th>
										  <th></th>
										  <th></th>
										  <th></th>
										  <th></th>
									  </tr>
									  @endif
									  @foreach($employer_placement_info as $employer_placement_row)
									  <tr>
										  <td valign="top">{{$employer_placement_row->name}}</td>
										  <td valign="top">{{$employer_placement_row->title}}</td>
										  <td valign="top">{{$employer_placement_row->start_date}}</td>
										  <td valign="top">{{date('Y-m-d',strtotime("+90 days", strtotime($employer_placement_row->start_date)))}}</td>
										  <td valign="top">{{"$ ".$employer_placement_row->base_salary}}</td>
										  <td valign="top"><?php
											  //$employer_placement_row->placement_fee = 20.00;
											  echo "$ ".(((((float)$employer_placement_row->base_salary)/100.00)*(float)$employer_placement_row->placement_fee)/100.00)*80.00;
											  ?>
										  </td>
									  </tr>
									  @endforeach
									  @if(count($agency_placement_info)>0)
									  <tr>
										  <th>Candidate</th>
										  <th width="30%">JOB</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
									  </tr>
									  @else
									  <tr>
										  <th></th>
										  <th width="30%"></th>
										  <th></th>
										  <th></th>
										  <th></th>
										  <th></th>
									  </tr>
									  @endif
								  </table>
                                </div>
                            </div>
                        
                        @endif

                            <div class="panel box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-html="true" href="#my_job_list" data-parent="#accordion_agency">
                                            My Jobs
                                        </a>
                                    </h4>
                                </div>
                                <div class="box-body panel-collapse collapse" id="my_job_list">
                                    <ul class="nav nav-pills nav-stacked">
                                        @foreach($stats_job as $key => $val)
                                            <li>
                                                <div class="pull-right">
                                                    <span class="pull-right" style="width: 31px;font-weight: 700;">{{ $val }}</span>
                                                </div>
                                                <h5>
                                                    <span style="width: 31px;font-weight: 700;">
                                                        {{ ucwords(str_replace('so','SO\'s',str_replace('_',' ',$key))) }}
                                                    </span>
                                                </h5>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        
                        @if(isset($stats_so))
                            <div class="panel box">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-html="true" href="#so_job_list" data-parent="#accordion_agency">
                                            My TalentGrams
                                        </a>
                                    </h4>
                                </div>
                                <div class="box-body panel-collapse collapse" id="so_job_list">
                                    <ul class="nav nav-pills nav-stacked">
                                            @foreach($stats_so as $key => $val)
                                                <li>
                                                    <div class="pull-right">
                                                        <span class="pull-right" style="width: 31px;font-weight: 700;">{{ $val }}</span>
                                                    </div>
                                                    <h5>
                                                        <span style="width: 31px;font-weight: 700;">
                                                            {{-- {{ ucwords(str_replace('so',' ',str_replace('_',' ',$key))) }} --}}
                                                            {{(strlen($key)<15)? ucwords(str_replace('so','TalentGrams ',str_replace('_',' ',$key))) : ucwords(str_replace('so',' ',str_replace('_',' ',$key)))  }}
                                                        </span>
                                                    </h5>
                                                </li>
                                            @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    
                    
                    
                    
                    
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

        </div>
        <!-- /.row -->
    </section>
@endsection
