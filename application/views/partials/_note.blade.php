@if($note->user_id == get_user('id'))
<div class="box-comment" style="padding: 10px;background-color: #fff;">
    @if($note->added_by->profile_pic)
        <img class="img-circle img-sm"  src="{{ base_url('public/uploads/'.$note->added_by->profile_pic) }}"  alt="user">
    @else
        <img class="img-circle img-sm"  src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="user">
    @endif
    <div class="comment-text">
        <span class="username">{{ $note->added_by->first_name." ".$note->added_by->last_name }}
            <!-- Need time and date  -->
            <!-- <i class="fa fa-clock-o text-muted pull-right"><?php // echo time_since($note->updated_at);?></i> -->
            <i class="text-muted pull-right" >{{ date('d M, Y', strtotime($note->updated_at))}} <?php // echo $note->updated_at;?></i>
        </span>
        <span style="white-space: pre-line;">{{ $note->feedback }} </span>
    </div>
</div>
@else
<div class="box-comment" style="padding: 10px;background-color: #e1e9ee;">
    @if($note->added_by->profile_pic)
        <img class="img-circle img-sm"  src="{{ base_url('public/uploads/'.$note->added_by->profile_pic) }}"  alt="user">
    @else
        <img class="img-circle img-sm"  src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="user">
    @endif
    <div class="comment-text">
        <span class="username">{{ $note->added_by->first_name." ".$note->added_by->last_name }}
            <!-- Need time and date  -->
            <!-- <i class="fa fa-clock-o text-muted pull-right"><?php // echo time_since($note->updated_at);?></i> -->
            <i class="text-muted pull-right" >{{ date('d M, Y', strtotime($note->updated_at))}} <?php // echo $note->updated_at;?></i>
        </span>
        <span style="white-space: pre-line;">{{ $note->feedback }} </span>
    </div>
</div>

@endif