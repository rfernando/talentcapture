
<li class="dropdown messages-menu" @if(!isset($subscription['type']))  @endif>
    <!-- Menu toggle button -->
    <!-- <a href="{{ base_url('messages') }}" {{--class="dropdown-toggle"--}} {{--data-toggle="dropdown"--}}>
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">{{ ($newMessages['unread']) ? $newMessages['unread'] : '' }}</span>
    </a> -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        @if(count($newMessages['unread_all_messages'])>0)
            <span class="label label-success">{{ ($newMessages['unread_all_messages']) ? count($newMessages['unread_all_messages']) : '' }}</span>
        @endif
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have {{ count($newMessages['unread_all_messages']) }} unread messages</li>
        <li>
            <!-- inner menu: contains the messages -->
            <ul class="menu">
                @foreach($newMessages['all_messages'] as $message)
                    <li><!-- start message -->

                        @if($message->type == 0)
                            <a href="{{ base_url('messages/chat/'.$message->from_user_id) }}" title="<?=($message->viewed=='0') ? 'Unread' :'Read'?>">
                        @else
                            <a href="{{ base_url('messages/chat/'.$message->from_user_id.'/'.$message->candidate_id .'/'.$message->job_id) }}" title="<?=($message->viewed=='0') ? 'Unread' :'Read'?>">
                        @endif

                            <div class="pull-left">
                                <span style="padding-right: 5px;<?=($message->viewed=='0') ? 'color::#00a65a;' :'color:#97a0b3;'?>" > 
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                </span>
                                <!-- User Image -->
                                @if($userImage = $message->from_user->profile_pic)
                                    <img src="{{ base_url('public/uploads/'.$userImage) }}" class="img-circle" alt="User Image">
                                @else
                                    <img src="{{ base_url('public/img/default_profile_pic.jpg') }}" class="img-circle" alt="User Image">
                                @endif
                            </div>
                            <!-- Message title and timestamp -->
                            <h4 style="<?=($message->viewed=='0') ? 'font-weight:bold;' :''?>">
                                {{ $message->from_user->first_name." ".$message->from_user->last_name }}
                                <small><i class="fa fa-clock-o"></i> {{ time_since($message->created_at) }}</small>
                            </h4>
                            <!-- The message display only 30 character -->
                            <p>{{  substr($message->text, 0, 30); }}</p>
                        </a>
                    </li>
                    <!-- end message -->
                @endforeach
            </ul>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="{{ base_url('messages') }}">See All Messages</a></li>
    </ul>
</li>