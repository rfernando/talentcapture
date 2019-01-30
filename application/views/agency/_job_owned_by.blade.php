@if(isset($job))
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-building"></i> {{ ucfirst($job->user->type)}} Profile</h3>
        </div>
        <!-- /.box-header -->

        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li>
                    <a href="{{ base_url('searches/job_owner_details/'.$job->id.'/'. $job->user->id) }}" >
                        <i class="fa fa-building-o"></i> {{ $job->user->user_profile()->first()->company_name }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /. box -->
@endif
