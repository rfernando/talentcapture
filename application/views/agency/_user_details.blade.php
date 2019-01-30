
@section('page-css')
@parent

<link rel="stylesheet" href="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.css') }}">
@endsection
<div class="box box-widget widget-user " style="height:40%">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header bg-light-blue-active">
        <h3 class="widget-user-username"> 
            {{ ucfirst($user->first_name)." ".ucfirst($user->last_name) }}
            <span class="pull-right" style="font-size: 0.5em">
                <?php 
                    $uagency = User::with('user_profile')->find($user->id);
                    $uagency->load('agency_ratings', 'user_profile');
                    $avgRating = 0;
                   // dd(DB::getQueryLog());
					$rating_count=0;

                    //Change reviews to start averaging after one review
                    if(count($uagency->agency_ratings)>0)
                    {
                        foreach ($uagency->agency_ratings as $rating) {
                            if($rating->pivot->rat_status==1)
    						{
    							$avgRating += $rating->pivot->rating;
    							$rating_count++;
    						}
                        }
                    }

                   if($rating_count>=1){

                        $avgRating = $avgRating/$rating_count;
                   }else{
                         $avgRating =0;
                   }
                    
                    
                 ?>
                <input type="text" class="star-rating" data-size="xs" value="{{ $avgRating }}">
            </span>
        </h3>

        <?php 

       /* echo "<pre>";
        print_r($user);
        echo "</pre>";*/
        ?>
        <h5 class="widget-user-desc">{{($user->user_profile->role != 'Other' ) ? $user->user_profile->role.' @ ' : '' }} {{ $user->user_profile->company_name }}</h5>
        @if(get_user('type') == 'agency')
            {{-- @if($isFavourite) --}}
            @if(count($user->favourite_agencies)>0)
                <a href="{{ base_url('agency/remove_favourite/'.$user->id) }}" class="widget-user-contact-link pull-left"><i class="fa fa-thumbs-o-down"></i> Remove from Preferred</a>
            @else
                <a href="{{ base_url('agency/add_favourite/'.$user->id) }}" class="widget-user-contact-link pull-left"><i class="fa fa-thumbs-o-up"></i> Add to Preferred</a>
            @endif
        @endif
        <a href="{{ base_url('messages/chat/'.$user->id) }}"  class="widget-user-contact-link pull-right"><i class="fa fa-envelope"></i> Send a Message</a>
    </div>
    <div class="widget-user-image">
        <img class="img-circle" src="{{ ($user->profile_pic) ? base_url('public/uploads/'.$user->profile_pic) :  base_url('public/img/default_profile_pic.jpg') }}" alt="User Avatar">
    </div>
    
    <div class="box-footer">
        <div class="row">
            {{--<div class="col-xs-6">
                <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                <p class="text-muted">
                    {{ $user->email }}
                </p>
            </div>--}}

            <div class="col-xs-6">
                <strong><i class="fa fa-globe margin-r-5"></i> Website</strong>
                <p class="text-muted ">
                    <a href="{{ $agency->user_profile->company_website_url }}" target="_blank">
                        {{ $user->user_profile->company_website_url }}
                    </a>
                </p>
            </div>

            <div class="col-xs-6"  style="padding-left: 120px">
                <span>
                    <a href="{{$agency->user_profile->linkedin_url}}" target="_blank"><i class="fa fa-linkedin-square fa-2x margin-r-10"></i></a>
                </span>

                    @if($agency->user_profile->facebook_url != '')
                        <span>
                            <a href="{{$agency->user_profile->facebook_url}}" target="_blank"><i class="fa fa-facebook-square fa-2x margin-r-10"></i></a>
                        </span>
                    @endif

                    @if($agency->user_profile->twitter_url != '')
                        <span>
                            <a href="{{$agency->user_profile->twitter_url}}" target="_blank"><i class="fa fa-twitter-square fa-2x margin-r-10"></i></a>
                        </span>
                    @endif

            </div>
        </div>
        
        <div class="row">
        @unless(!$user->user_profile->company_address)
                <div class="col-xs-12">
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                    <address>
                        {{ $user->user_profile->company_name }}
                        , {{ $user->user_profile->company_address }}
                        , {{ $user->user_profile->city }},
                        {{ ($user->user_profile->state()->first() != '') ? $user->user_profile->state()->first()->abbreviation : ''}} {{ $user->user_profile->zipcode }}
                        <br>
                        {{--<abbr title="Phone">P:</abbr>(123) 456-7890--}}
                    </address>
                </div>
            @endunless
        </div>

        <div class="row">
        @unless(!$user->phone)
            <div class="col-xs-12">
                <strong><i class="fa fa-phone margin-r-5"></i> Phone</strong>
                <address>
                     {{ $user->phone }}
                    <br>
                </address>
            </div>
        @endunless
        </div> 
        <div class="row">
        @unless(!$user->industries()->lists('title')['0'])
            <div class="col-xs-12">
                <strong><i class="fa fa-industry margin-r-5"></i> Industries</strong>
                <address>
                     {{ implode(',', $user->industries()->lists('title')) }}
                    <br>
                </address>
            </div>
        @endunless
        </div>
        <div class="row">
            @unless(!$user->professions()->lists('title')['0'])
            <div class="col-xs-12">
                <strong><i class="fa fa-users margin-r-5"></i> Professions</strong>
                <address>
                    {{  implode(',', $user->professions()->lists('title')) }}   
                </address>
            </div>
             @endunless
        </div>
        <hr>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#description" data-toggle="tab"><i class="fa fa-info-circle"></i> Description</a></li>
                <li><a href="#review" data-toggle="tab"><i class="fa fa-star"></i> Reviews</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="description">
                    <p>{{ $user->user_profile->company_desc }}</p>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="review">
                    @if(count($user->agency_ratings))
                        <div class="box-footer box-comments">
                            @each('partials._review', $user->agency_ratings, 'ratings')
                        </div>
                    @else
                        <p>No new reviews.</p>
                    @endif
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.row -->
    </div>
</div>

@section('page-js')
@parent
<!--Bootstrap Star Rating-->
<script src="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.js') }}"></script>
@endsection


@section('end-script')
    @parent

    <script type="application/javascript">
        $('document').ready(function(){

            $(".star-rating").rating({
                "size":"xs",
                "clearButton" : '',
                "showCaption" : false,
                "readonly" : true
            });
        });

    </script>
@endsection