<?php // if($msg->to_user_id == get_user('id') && $msg->type=='1') { } else { ?>
    <div class="direct-chat-msg {{ ($msg->from_user_id == get_user('id')) ? 'right' : '' }}">
        <div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-{{ ($msg->from_user_id == get_user('id')) ? 'left' : 'right' }}">{{ $msg->from_user->first_name. ' '. $msg->from_user->last_name }}
            </span>

            <span class="direct-chat-timestamp pull-{{ ($msg->from_user_id == get_user('id')) ? 'right' : 'left' }}">{{ date('M d, Y H:i A', strtotime($msg->created_at)) }}</span>
        </div>
       
        <!-- /.direct-chat-info -->
        @if($profileImg = $msg->from_user->profile_pic)
            <img class="direct-chat-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
        @else
            <img class="direct-chat-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
        @endif
        <div class="direct-chat-text"><span style="font-size: 10px;"><?php 
            if($msg->type=='1' && $msg->job_id!='0')
            {
                $candiadtes = Candidate::find($msg->candidate_id);
                $jobs = Job::find($msg->job_id);
                $logged_in_user_id = get_user('id');
            ?>
            @if($jobs->user_id == $logged_in_user_id)
            <a href="{{ base_url('jobs/candidate_detail/'.$msg->job_id.'/'.$msg->candidate_id) }}"  title="Candidate Detail">{{ $candiadtes->name }}</a> | <a href="{{ base_url('jobs/view_detail/'.$msg->job_id) }}"  title="Job Detail"> {{ $jobs->title }}</a>
            @else
            <a href="{{ base_url('jobs/candidate_detail/'.$msg->job_id.'/'.$msg->candidate_id) }}"  title="Candidate Detail">{{ $candiadtes->name }}</a> | <a href="{{ base_url('searches/job_detail/'.$msg->job_id. '/any/any') }}"  title="TalentGram Detail"> {{ $jobs->title }}</a>
            @endif
            <?php } ?></span>{{ $msg->text }}</div>
    </div>
<?php  // } ?>
