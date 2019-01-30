<?php 
    $subscription = check_subscription();
    $user= get_user();
?>
@if(isset($subscription['dstatus']) && $subscription['dstatus']=='inactive' && $user['type']=='agency')
    <li class="search_subscription">
        <section class="search_subscription">
            <div class="alert alert-{{ $subscription['type'] }}">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                The agency search feature requires an active subscription. You can review subscription options <a href="<?=base_url('agency/subscription_plans');?>">here.</a>
            </div>
        </section>
    </li>
@else
    @if(count($usersList)>0)
        @foreach($usersList as $agency)
    	<?php

    try{
    		$avgRating = 0;
    		$rating_count=0;
    		$this->data['agency123'] = User::with('user_profile')->find($agency->id);
            if($this->data['agency123'])
    		{
    			$this->data['agency123']->load('agency_ratings', 'user_profile');
    			if(count($this->data['agency123']->agency_ratings)>0){
                foreach ($this->data['agency123']->agency_ratings as $rating)
                    {
    					if($rating->pivot->rat_status==1)
    						{
    							$avgRating += $rating->pivot->rating;
    							$rating_count++;
    						}
    				}
    			
                if($rating_count>=1){

                            $avgRating = $avgRating/$rating_count;
                       }else{
                             $avgRating =0;
                       }
            	}
    		}
            else{
    		$avgRating =0;
    		}
            $this->data['avgRating'] = $avgRating;
    	?>
           
           
            <li>
                <a style="height: 70px" href="{{ base_url("agency/detail/$agency->id") }}">
                    @if($profileImg = $agency->profile_pic)
                        <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                    @else
                        <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                    @endif
                    <div class="contacts-list-info">
                        <span class="contacts-list-name">{{ $agency->first_name }} {{ $agency->last_name }} </span>
                        <span class="contacts-list-msg">{{ $agency->company_name }}</span>
                        <br>
            			<span class="pull-left" style="font-size: 0.5em"><input type="text" class="star-rating" data-size="xs" value="{{$avgRating}}"></span>
                    </div>
                </a>
            </li>
            <?php } 
    catch (Exception $ex){}
    ?>       		
        @endforeach
    @else
        <li>
            <a href="javascript:void(0)">Sorry, No Results Found</a>
        </li>
    @endif
@endif