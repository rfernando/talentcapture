@extends('template.template')

@section('page-css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/select2/select2.min.css') }}">
@endsection

@section('main-content')
        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        User Profile
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><i class="fa fa-image"></i> User Profile</a></li>
        <li class="active"><i class="fa fa-pencil"></i> Update</li>
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
                        <img class="profile-user-img img-responsive img-circle" src="{{ base_url('public/uploads/'.$user->profile_pic) }}" alt="User profile picture">
                    @else
                        <img class="profile-user-img img-responsive img-circle" src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="User profile Picture">
                    @endif
                    <h3 class="profile-username text-center">{{ get_user('first_name')." ".get_user('last_name') }}</h3>

                    <p class="text-muted text-center">{{ get_user('email') }}</p>

                    {{--<ul class="list-group list-group-unbordered">
                        @foreach($stats as $key => $val)
                            <li class="list-group-item">
                                <b>{{ ucwords(str_replace('so','SO\'s',str_replace('_',' ',$key))) }}</b> <a class="pull-right">{{ $val }}</a>
                            </li>
                        @endforeach
                    </ul>--}}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Company Details </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if($user_profile != '')
                        <strong><i class="fa fa-building margin-r-5"></i> Name</strong>

                        <p class="text-muted">{{ $user_profile->company_name }}</p>

                        <hr>

                        <!-- <strong><i class="fa fa-certificate margin-r-5"></i> Professions11</strong>
                        <p class="text-muted">{{ $user_profile->role }}</p>
                        <hr> -->

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
                            <a href="{{ $user_profile->company_website_url }}" target="_blank">
                                {{ $user_profile->company_website_url }}
                            </a>
                        </p>

                        {{--<hr>

                        <strong><i class="fa fa-file-text-o margin-r-5"></i> Descriptions</strong>

                        <p>{{ $user_profile->company_desc }}</p>--}}
                    @else
                        <p>User has not Updated Complete Profile Information yet</p>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom update-profile-user">
                <ul class="nav nav-tabs">
                    <li class="{{ $profilegactive }}"><a href="#update-profile" data-toggle="tab"><i class="fa fa-pencil"></i> Update Profile</a></li>
                    <li><a href="#profile-picture" data-toggle="tab"><i class="fa fa-image"></i> Profile Picture</a></li>
                    <li><a href="#speciality-areas" data-toggle="tab"><i class="fa fa-certificate"></i> {{ (get_user('type') == 'agency') ? 'Specialty Areas' : 'Industry Setting' }}</a></li>
                    <li class="{{ $settingactive }}"><a href="#settings" data-toggle="tab"><i class="fa fa-wrench"></i> Password</a></li>
                @if(get_user('type')=='agency')
                    <li class="{{ $myplan }}"><a href="#my-plan" data-toggle="tab"><i class="fa fa-subscript"></i> My Plan</a></li>
                @endif
                    <li><a href="#performance-summary" data-toggle="tab"> Analytics</a></li>
                    <li><a href="#my-charities" data-toggle="tab"> My Charities</a></li>
                </ul>

                <div class="tab-content">
                    <div class="{{ $profilegactive }} tab-pane" id="update-profile">
                        <h4>Update your Profile</h4>
                        <hr>
                        {{ flash_msg() }}
                        <form action="{{ base_url('Profile/save_profile') }}"  class="form-horizontal validateForm" method="post">
                            {{ generate_form_fields($updateProfileFields, 3) }}

                            <!-- Label and placeholder are diffrent so we need to add here, only for agency -->
                            @if(get_user('type')=='agency')
                            <div class="form-group">
                                <label for="user_profiles-recruiter_profile" id="label-user_profiles-recruiter_profile" class="col-sm-3 control-label">Recruiter Profile</label>
                                <div class="col-sm-9">
                                    <textarea name="user_profiles[recruiter_profile]" cols="40" rows="5" placeholder="This is where you showcase your personal recruiting talent. Tell hiring managers about your experience and why they should work with you."  data-required="true" id="user_profiles-recruiter_profile" class="form-control"><?=$user_profile->recruiter_profile;?></textarea>
                                </div>
                            </div>
                            @endif

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="profile-picture">
                        <form action="<?php echo base_url('profile/upload_profile_pic')?>" id="upload-profile-pic-form" method="POST"  enctype="multipart/form-data">
                            <h4>Upload a Profile Picture</h4>
                            <hr>
                            <div class="form-group">
                                <label for="exampleInputFile">Profile Picture</label>
                                <input id="select_profile_pic" type="file" name="profile_pic" >
                                <p class="help-block">Select an Image to upload</p>
                            </div>
                            <div class="form-group">
                            @if($user->profile_pic != '')
                                <img class="img-responsive img-thumbnail" src="{{ base_url('public/uploads/'.$user->profile_pic) }}" id="profile-pic-preview" alt="Profile Pic Preview">
                            @else
                                <img class="img-responsive img-thumbnail" src="{{ base_url('public/img/default_profile_pic.jpg') }}" id="profile-pic-preview" alt="Profile Pic Preview">
                            @endif
                            </div>

                            <div class="form-group">
                                    <button type="submit" class="btn btn-danger">Upload</button>
                            </div>

                        </form>

                    </div>

                    <div class="tab-pane" id="speciality-areas">
                        <form action="{{ base_url('profile/save_specializations') }}" class="form-horizontal" method="POST">
                            @if(get_user('type') == 'agency')
                                <div class="alert alert-info">Here you can select up to five industries and corresponding professions you specialize in as an agency recruiter.</div>
                                <div class="form-group">
                                    <label for="speciality-areas-field" class="col-sm-3 control-label">Industries</label>
                                    <div class="col-sm-7">
                                        {{ form_dropdown('industries[]',$industries, $industrySpecialization,'id="specializations" multiple class="form-control select2" style="width: 100%;"' ) }}
                                    </div>
                                    @if($user->type == 'employer')
                                        <div class="checkbox col-sm-2" style="clear: none;">
                                            <label>
                                                <input type="checkbox" id="select-all-industries"> Select All
                                            </label>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="speciality-areas-field" class="col-sm-3 control-label">Professions</label>
                                    <div class="col-sm-7">
                                        {{ form_dropdown('professions[]',$profession, $professionSpecialization,'id="profession" multiple class="form-control" style="width: 100%;"' ) }}
                                    </div>
                                    @if($user->type == 'agency')
                                        <div class="checkbox col-sm-2" style="clear: none;">
                                            <label>
                                                <input type="checkbox" id="select-all-profession"> Select All
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="form-group">

                                    <label for="speciality-areas-field" class="col-sm-3 control-label">Industry</label>
                                    <div class="col-sm-7">
                                        {{ form_dropdown('industries[]',$industries, $industrySpecialization[0],'class="form-control" style="width: 100%;"' ) }}
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="{{ $settingactive }} tab-pane" id="settings">
                        <h4>Change Password </h4>
                        <hr>
                        {{ flash_msg() }}
                        <form action="{{ base_url('Profile/change_password') }}" class="form-horizontal validateForm" method="post" novalidate="novalidate">
                            <div class="form-group">
                                <label for="users-first_name" class="col-sm-3 control-label">Current Password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="old_pass" placeholder="Current Password" data-required="true" class="form-control" aria-required="true" aria-invalid="false">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="users-first_name" class="col-sm-3 control-label">New Password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="new_pass" placeholder="New Password" data-required="true" class="form-control" aria-required="true" aria-invalid="false">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="users-first_name" class="col-sm-3 control-label">Confirm New Password</label>
                                <div class="col-sm-9">
                                    <input type="password" name="confirm_new_pass" placeholder="Confirm New Password" data-required="true" class="form-control" aria-required="true" aria-invalid="false">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @if(get_user('type')=='agency')
                    <div class="{{ $myplan }} tab-pane" id="my-plan">

                        <h4>My Plan </h4>
                        <hr>
                        {{ flash_msg() }}
                        @if($users_plan['type'] !="")
                            <div class="alert alert-{{ $users_plan['type'] }}">
                                {{ $users_plan['msg'] }}
                            </div>
                        @else
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="users-plan_name" class="col-sm-3 control-label">Plan Name</label>
                                    <div class="col-sm-9">
                                        <label for="users-plan_name" class="control-label">{{ $users_plan['my_plan']->plan_name }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="users-amount" class="col-sm-3 control-label">Amount</label>
                                    <div class="col-sm-9">
                                        <label for="users-amount" class="control-label"><i class="fa fa-usd" aria-hidden="true"></i>
                                            {{ $users_plan['my_plan']->amount }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="users-start_date" class="col-sm-3 control-label">Start Date</label>
                                    <div class="col-sm-9">
                                        <label for="users-start_date" class="control-label">{{ date("m-d-Y",strtotime($users_plan['my_plan']->created_at)) }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="users-start_date" class="col-sm-3 control-label">No of Days</label>
                                    <div class="col-sm-9">
                                        <label for="users-start_date" class="control-label">{{ $users_plan['my_plan']->no_of_days }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="users-days_remaining" class="col-sm-3 control-label">Days Remaining</label>
                                    <div class="col-sm-9">
                                        <?php
                                            $date1=date("Y-m-d",strtotime($users_plan['my_plan']->created_at));
                                            $date2=date("Y-m-d");
                                            $days = $users_plan['my_plan']->no_of_days - date_difference($date1,$date2);
                                        ?>
                                        <label for="users-days_remaining" class="control-label">{{ $days." Days" }}</label>
                                    </div>
                                </div>
                                @if(isset($subscription_plans->description))
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Description</label>
                                        <div class="col-sm-9">
                                            <label class="control-label" style="text-align:left;">{{ $subscription_plans->description }}</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                    <div class="tab-pane" id="performance-summary">
                        <h4>Analytics</h4>
                        <div class="box-group" id="accordion_agency">
                        @if(get_user('type')=='agency')
                        
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
									  <tr>
										  <th>Candidate</th>
										  <th width="30%">JOB</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
                                          <th>Action</th>
									  </tr>
									  @foreach($agency_placement_info as $agency_placement_row)
									  <tr>
										  <td valign="top">{{$agency_placement_row->name}}</td>
										  <td valign="top">{{$agency_placement_row->title}}</td>
										  <td valign="top">{{$agency_placement_row->start_date}}</td>
										  <td valign="top">{{date('Y-m-d',strtotime("+".(int)$agency_placement_row->warranty_period." days", strtotime($agency_placement_row->start_date)))}}</td>
										  <td valign="top">{{"$ ".(str_replace(",","",$agency_placement_row->base_salary))}}</td>
										  <td valign="top"><?php
											  //$agency_placement_row->split_percentage = 15.00;
											  echo "$ ".((float)(str_replace(",","",$agency_placement_row->base_salary)))*((float)$agency_placement_row->placement_fee/100.00)*((float)$agency_placement_row->split_percentage/100.00);
											  
											  ?></td>
                                        <td>
                                            <a href="{{ base_url('profile/cancel_reported_hire/'.$agency_placement_row->id.'/'.$agency_placement_row->candidate_id) }}" 
                                           data-toggle="tooltip" title="Cancel Reported Hires" onclick="return confirm('Are you sure you want to cancel this reported hire?')"><i class="fa fa-trash" style="color:red"></i></a>
                                        </td>
									  </tr>
									  @endforeach
									  <tr>
										  <th>Candidate</th>
										  <th width="30%">JOB</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
                                          <th>Action</th>
									  </tr>
									  
									  <tr><th align="left" colspan="6"><br>At Employers TalentGrams</th></tr>
									  <tr>
										  <th>Candidate</th>
										  <th width="30%">JOB</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
                                          <th>Action</th>
									  </tr>
									  @foreach($employer_placement_info as $employer_placement_row)
									  <tr>
										  <td valign="top">{{$employer_placement_row->name}}</td>
										  <td valign="top">{{$employer_placement_row->title}}</td>
										  <td valign="top">{{$employer_placement_row->start_date}}</td>
										  <td valign="top">{{date('Y-m-d',strtotime("+90 days", strtotime($employer_placement_row->start_date)))}}</td>
										  <td valign="top">{{"$ ".(str_replace(",","",$employer_placement_row->base_salary))}}</td>
										  <td valign="top"><?php
											  //$employer_placement_row->placement_fee = 20.00;
											  echo "$ ".(((((float)(str_replace(",","",$employer_placement_row->base_salary)))/100.00)*(float)$employer_placement_row->placement_fee)/100.00)*80.00;
                                                //echo "$ ".(float)$employer_placement_row->base_salary)/100.00;
											  ?>
										  </td>
                                          <td valign="top">
                                                <a href="{{ base_url('profile/cancel_reported_hire/'.$employer_placement_row->id.'/'.$employer_placement_row->candidate_id) }}" 
                                           data-toggle="tooltip" title="Cancel Reported Hires" onclick="return confirm('Are you sure you want to cancel this reported hire?')"><i class="fa fa-trash" style="color:red"></i></a>
                                            </td>
									  </tr>
									  @endforeach
									  <tr>
										  <th>Candidate</th>
										  <th width="30%">JOB</th>
										  <th>Start Date &nbsp; &nbsp;</th>
										  <th>Guarantee End Date</th>
										  <th>Salary</th>
										  <th>Placement Fee</th>
                                          <th>Action</th>
									  </tr>  
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

                    <div class="tab-pane" id="my-charities">
                        <h4>My Charities</h4>

                        <form action="{{ base_url('profile/save_charities') }}" class="form-horizontal" method="POST">
                            
                            <div class="alert alert-info">A portion of all proceeds TalentCapture receives are donated to the charity organizations listed below. Please select three charities you would like any proceeds from your company to be indirectly donated to.</div>
                            <div class="form-group">
                                <label for="speciality-areas-field" class="col-sm-3 control-label">My Charities</label>
                                <div class="col-sm-7">
                                    {{ form_dropdown('my_charities[]',$my_charities, $myCharities,'id="mycharities" multiple class="form-control select2" style="width: 100%;"' ) }}
                                </div>
                                
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>                      
                    </div>

                    <!-- /.tab-pane  -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection

@section('page-js')
    <!-- Select2 -->
    <script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>
@endsection

@section('end-script')
    <!-- Page script -->
    <script>
    $(function () {
        //Initialize Select2 Elements
        //var select2 = $(".select2");

        $("#specializations").select2({maximumSelectionLength : 10});
        $("#mycharities").select2({maximumSelectionLength : 3});

        var $profession = $('#profession').select2({allowClear: true});

        //var $profession = $('#profession').select2();

        $("#select-all-industries").click(function(){
            if($("#select-all-industries").is(':checked') ){
                $("#specializations > option").prop("selected","selected");
                $("#specializations").trigger("change");
            }else{
                $("#specializations > option").removeAttr("selected");
                $("#profession > option").removeAttr("selected");
                $("#specializations").trigger("change");
                $("#profession").trigger("change");
            }
        });

        $("#select-all-profession").click(function(){
            if($("#select-all-profession").is(':checked') ){
                $("#profession > option").prop("selected","selected");
                $("#profession").trigger("change");
            }else{
                $("#profession > option").removeAttr("selected");
                $("#profession").trigger("change");
            }
        });


        $("#specializations").on('change', function (evt) {
            $profession.select2('data', {id: null, text: null});

            var currentValues = $(this).val();
            $.ajax({
                url: '{{ base_url('Profile/getProfessionOptions') }}',
                data : {"industries" : currentValues},
                method : 'post',
                dataType : 'json',
                success: function(result){

                    console.log(result) ;
                    //$profession.val(result).trigger('change');
                    $profession.select2({data : result});
                }
            });
        });

        $('#user_profiles-linkedin_url').on('focus', function(){
            var value = $(this).val();
            if(value == '' )
                $(this).val('http://');
        }).on('blur',function(){
            var value = $(this).val();
            if(value != '' && value.indexOf('http://') != 0 && value.indexOf('https://') != 0){
                $(this).val('http://'+value);
            }else if(value == 'http://'){
                $(this).val('');
            }


        });

        $('#user_profiles-company_website_url').on('focus', function(){
            var value = $(this).val();
            if(value == '' )
                $(this).val('http://');
        }).on('blur',function(){
            var value = $(this).val();
            if(value != '' && value.indexOf('http://') != 0 && value.indexOf('https://') != 0){
                $(this).val('http://'+value);
            }else if(value == 'http://'){
                $(this).val('');
            }


        });

        $('#user_profiles-facebook_url').on('focus', function(){
            var value = $(this).val();
            if(value == '' )
                $(this).val('http://');
        }).on('blur',function(){
            var value = $(this).val();
            if(value != '' && value.indexOf('http://') != 0 && value.indexOf('https://') != 0){
                $(this).val('http://'+value);
            }else if(value == 'http://'){
                $(this).val('');
            }
        });

        $('#user_profiles-twitter_url').on('focus', function(){
            var value = $(this).val();
            if(value == '' )
                $(this).val('http://');
        }).on('blur',function(){
            var value = $(this).val();
            if(value != '' && value.indexOf('http://') != 0 && value.indexOf('https://') != 0){
                $(this).val('http://'+value);
            }else if(value == 'http://'){
                $(this).val('');
            }
        });

        

    });

    </script>
@endsection