<div class="box box-success direct-chat direct-chat-success">
    <div class="box-header with-border">
        <h3 class="box-title">Direct Chat</h3>
        <div class="box-footer">
        <form action="{{ base_url('messages/send') }}" method="post" class="ajax" id="chatForm" data-success="addMsgToChat">

            <?php
            if($messages->count()>0) {
             
            ?> 
            <input type="hidden" name="job_id" value="<?=($job_id=='0') ? $messages['0']->job_id : $job_id; ?>" style="display: none;">
            <?php // if($messages['0']->from_user_id == get_user('id') && $messages['0']->type=='1' && $messages['0']->candidate_id!='0') { 

            if($candidateid <> 0) {
            
                $candiadtes = Candidate::find($candidateid);
                $jobs = Job::find($job_id);
                $logged_in_user_id = get_user('id');
            ?> 
            <div class="input-group">
                <input type="checkbox" name="type" value="1" checked="checked">
                <input type="hidden" name="candidate_id" value="<?=$candidateid;?>">
                <a data-toggle="popover" data-trigger="hover" data-content="Select this option if you are responding to the last message." style="cursor: pointer;">
                 This message is regarding  
                </a> 
                @if($jobs->user_id == $logged_in_user_id)
                    <a href="{{ base_url('jobs/candidate_detail/'.$jobs->id.'/'.$candiadtes->id) }}"  title="Candidate Detail">{{ $candiadtes->name }}</a> | <a href="{{ base_url('jobs/view_detail/'.$jobs->id) }}"  title="Job Detail"> {{ $jobs->title }}</a>
                @else
                    <a href="{{ base_url('jobs/candidate_detail/'.$jobs->id.'/'.$candiadtes->id) }}"  title="Candidate Detail">{{ $candiadtes->name }}</a> | <a href="{{ base_url('searches/job_detail/'.$jobs->id. '/any/any') }}"  title="TalentGram Detail"> {{ $jobs->title }}</a>
                @endif

            </div>
            <?php }}
            else if($job_id!='0'){?>
                <input type="hidden" name="job_id" value="{{$job_id}}" style="display: none;">
                <input type="hidden" name="type" value="1" style="display: none;">
            <?php } ?>

            <?php // if($messages['0']->from_user_id == get_user('id') && $messages['0']->type=='1' && $messages['0']->candidate_id!='0') { 
            if(!empty($candidateid)) {
            ?> 
            <div class="input-group">
                <input type="checkbox" name="candidateDisc" value="1" checked="checked" style="display: none;">
                <input type="hidden" name="to_user_id" value="{{ $toUserID }}">
                <input type="hidden" name="candidate_id" value="{{ $candidateid }}">
                <!-- <input type="hidden" name="candidate_id" value="<?=$messages['0']->candidate_id;?>"> -->
            </div>
            <?php } ?>

            <div class="input-group">
                <input type="hidden" name="to_user_id" value="{{ $toUserID }}">
                @if($enableSend == 1)
                    <textarea rows="4" cols="50" id="text_enable" name="text" placeholder="Type Message ..." class="form-control" required></textarea>
                @else
                    @if($enableSend == 2)
                        <input type="text" id="text_disable" name="text" placeholder="This user must first accept you as an approved agency recruiter for this TalentGram before you can send a message." class="form-control" required>
                    @else
                        <input type="text" id="text_disable" name="text" placeholder="You no longer have the ability to send messages to this user." class="form-control" required>
                    @endif
                @endif
                
                  <span class="input-group-btn">
                    @if($enableSend>0)
                        <button type="submit" class="btn btn-success btn-flat" style="margin-left: 10px;">Send</button>
                    @else
                        <button type="submit" class="btn btn-success btn-flat disabled">Send</button>
                    @endif

                  </span>
            </div>
        </form>
    </div>

        {{--<div class="box-tools pull-right">
            <span data-toggle="tooltip" title="3 New Messages" class="badge bg-green">3</span>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            --}}{{--<button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                <i class="fa fa-comments"></i></button>--}}{{--
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>--}}
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- Conversations are loaded here -->
        <div class="direct-chat-messages" id="chatMsg">
            <!-- Message. Default to the left -->
          @each('messages._msg', $messages, 'msg')
        </div>
        <!--/.direct-chat-messages-->

        <!-- Contacts are loaded here -->
        {{--<div class="direct-chat-contacts">
            <ul class="contacts-list">
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="{{ base_url('public/uploads/user22.jpg')  }}" alt="User Image">
                        <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              Count Dracula
                              <small class="contacts-list-date pull-right">2/28/2015</small>
                            </span>
                            <span class="contacts-list-msg">How have you been? I was...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                    </a>
                </li>
                <!-- End Contact Item -->
            </ul>
            <!-- /.contatcts-list -->
        </div>--}}
        <!-- /.direct-chat-pane -->
    </div>
    <!-- /.box-body -->
    <!-- /.box-footer-->
</div>


@section('end-script')
    @parent

    <!-- Select2 -->
    <script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>
    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
    <!-- FastClick -->
    <script src="{{ admin_assets_url('plugins/fastclick/fastclick.js') }}"></script>

    <script type="application/javascript">
        var chatMsg = $('#chatMsg');

        $(document).ready(function () {
            //chatMsg.scrollTop(chatMsg[0].scrollHeight);
            chatMsg.scrollTop(0);
        })
        function addMsgToChat(result, element) {
            /*Remove append and add prepend because i need to add latest msg on top*/
            chatMsg.prepend(result);
            //chatMsg.scrollTop(chatMsg[0].scrollHeight);
            chatMsg.scrollTop(0);
        }
        $('#text_disable').attr('disabled', 'disabled')

        

    </script>
@endsection