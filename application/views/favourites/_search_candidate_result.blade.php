<!-- candidate results -->
<?php 
    $subscription = check_subscription();
    $user= get_user();
?>
@if(isset($subscription['dstatus']) && $subscription['dstatus']=='inactive' && $user['type']=='agency')
    <li class="search_subscription">
        <section class="search_subscription">
            <div class="alert alert-{{ $subscription['type'] }}">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                The Candidate search feature requires an active subscription. You can review subscription options <a href="<?=base_url('agency/subscription_plans');?>">here.</a>
            </div>
        </section>
    </li>
@else
    @if(count($usersList)>0)
        @foreach($usersList as $candidate)
    	<?php
        try{
    	?>
            <li>
                @if($candidate->closed != 1)
                <a style="height: 70px" href="{{ base_url("searches/candidate_detail/$candidate->id/$candidate->candidate_id/any/none") }}">
                    <div>
                        <span>{{ $candidate->candiate_name }}</span><br>
                        <span>{{ $candidate->title }}</span>
                        <br>
            			<span>{{ $candidate->city }},{{ $candidate->name }}</span>
                    </div>
                </a>
                @else
                <a style="height: 70px" href="{{ base_url("searches/candidate_detail/$candidate->id/$candidate->candidate_id/none/any") }}">
                    <div>
                        <span>{{ $candidate->candiate_name }}</span><br>
                        <span>{{ $candidate->title }}</span>
                        <br>
                        <span>{{ $candidate->city }},{{ $candidate->name }}</span>
                    </div>
                </a>
                @endif
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