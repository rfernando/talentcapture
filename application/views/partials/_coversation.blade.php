@if($coversation->count())
	<?php $conversation_subject = $coversation->conversation_subject;
		if (strpos($conversation_subject, 'Re:') !== false) {
    		$style = "white-space:pre";
		}else{
			$style="";
		}
	?>
    <div class="box-comment">
        <img class="img-circle img-sm"  src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="user">
        <div class="comment-text">
            <span class="username">
                <i class="text-muted pull-right" >{{ date('d M, Y', strtotime($coversation->updated_at))}} </i>
            </span>
            <span style="<?php echo $style; ?>">{{ $coversation->last_conversation }} </span>
        </div>
    </div>
@endif


