<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-building"></i> Approved {{($agencies->count() > 1) ? 'Agencies' : 'Agency' }}</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body {{ $agencies->count() ? 'no-padding' : '' }}">
        @if($agencies->count())
            <ul class="nav nav-pills nav-stacked">
                @foreach($agencies as $c)
                    <?php $checkstatus = Accepted_job::where('user_id','=',$c->id)->where('job_id','=',$job->id)->first(); ?>
                    @if($checkstatus->estatus == 1)
                    <li {{ (isset($agency->id) && $agency->id == $c->id) ? 'class="active"' : '' }}>
                        @if(isset($userType) && $userType == 'admin')
                            <a href="{{ base_url('admin/jobs/agency_detail/'.$job->id.'/'. $c->id) }}" >
                                <i class="fa fa-building-o"></i>
                                {{ (isset($c->user_profile->company_name)) ? $c->user_profile->company_name : $candidate->agency()->first()->user_profile()->first()->company_name.
                                '<!--<i class="fa fa-envelope pull-right send_msg_icon" data-toggle="tooltip" data-agency_id="'.$candidate->agency()->first()->id.'" title="Send a Message"></i>-->' }}
                    
                            </a>
                        @else
                            <a href="{{ base_url('jobs/agency_detail/'.$job->id.'/'. $c->id) }}" >
                                <i class="fa fa-building-o"></i>
                                {{ (isset($c->user_profile->company_name)) ? $c->user_profile->company_name : $candidate->agency()->first()->user_profile()->first()->company_name.
                                '<!--<i class="fa fa-envelope pull-right send_msg_icon" data-toggle="tooltip" data-agency_id="'.$candidate->agency()->first()->id.'" title="Send a Message"></i>-->' }}


                            </a>
                        @endif

                    </li>
                    @endif
                                
                @endforeach
            </ul>
        @else
            <p class="text-info">No agencies have yet accepted this Search Opportunity.</p>
        @endif
    </div>
</div>
<!-- /. box -->


@section('end-script')

    @parent
    <script>
        $('.send_msg_icon').click(function () {
            var agency_id = $(this).data('agency_id');
            console.log(agency_id);
            window.location = baseURL + 'messages/chat/' + agency_id ;
            //$('#sendMsg').modal();
            return false;
        });
    </script>
@endsection