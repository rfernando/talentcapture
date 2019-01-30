
@section('page-css')
    @parent

    <link rel="stylesheet" href="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.css') }}">
@endsection
<div class="modal" id="sendMsg">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-envelope"></i> Send a Message</h4>
            </div>
            <div class="modal-body">
                <form action="{{ base_url('messages/send') }}" method="post" id="chatForm" >
                    <div class="form-group">
                        <input type="hidden" name="to_user_id" value="{{ $user->id }}">
                        <textarea name="text" placeholder="Type Message ..." class="form-control" rows="10"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" data-submit="#chatForm">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="box box-widget widget-user">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header bg-light-blue-active">
        <h3 class="widget-user-username">
            {{ $user->first_name." ".$user->last_name }}
            <span class="pull-right" style="font-size: 0.5em">
                <input type="text" class="star-rating" data-size="xs" value="{{ 3 }}">
            </span>
        </h3>
        <h5 class="widget-user-desc">{{($user->user_profile->role != 'Other' ) ? $user->user_profile->role.' @ ' : '' }} {{ $user->user_profile->company_name }}</h5>
        @if($usertype == 'agency')
            @if($isFavourite)
                <a href="{{ base_url('agency/remove_favourite/'.$user->id) }}" class="widget-user-contact-link pull-left"><i class="fa fa-thumbs-o-down"></i> Remove from Preferred</a>
            @else
                <a href="{{ base_url('agency/add_favourite/'.$user->id) }}" class="widget-user-contact-link pull-left"><i class="fa fa-thumbs-o-up"></i> Add to Preferred</a>
            @endif
        @endif
        @unless($usertype != 'agency' && isset($hasAcceptedCandidate) && !$hasAcceptedCandidate)
            <a href="{{ base_url('messages/chat/'.$user->id) }}" class="widget-user-contact-link pull-right"><i class="fa fa-envelope"></i> Send a Message</a>
        @endunless
    </div>
    <div class="widget-user-image">
        <img class="img-circle" src="{{ ($user->profile_pic) ? base_url('public/uploads/'.$user->profile_pic) :  base_url('public/img/default_profile_pic.jpg') }}" alt="User Avatar">
    </div>
    {{--<div class="box-footer">
        <div class="row">
            <div class="col-sm-4 border-right">
                <div class="description-block">
                    <h5 class="description-header">3,200</h5>
                    <span class="description-text">SALES</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 border-right">
                <div class="description-block">
                    <h5 class="description-header">13,000</h5>
                    <span class="description-text">FOLLOWERS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4">
                <div class="description-block">
                    <h5 class="description-header">35</h5>
                    <span class="description-text">PRODUCTS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
        </div>
    </div>--}}
    <div class="box-footer">
        {{--<div class="row">
            <div class="col-xs-6">
                <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                <p class="text-muted">
                    {{ $user->email }}
                </p>
            </div>
            <div class="col-xs-6">
                <strong class="pull-right"><i class="fa fa-linkedin-square margin-r-5"></i> LinkedIn Profile</strong>
                <p class="pull-right">
                    <a href="{{ $user->user_profile->linkedin_url }}">
                        {{ $user->user_profile->linkedin_url  }}
                    </a>
                </p>
            </div>
        </div>
        <hr>--}}
        <div class="row">
            <div class="col-xs-6">
                <strong><i class="fa fa-globe margin-r-5"></i> Website</strong>
                <p class="text-muted ">
                    <a href="{{ $user->user_profile->company_website_url }}" target="_blank">
                        {{ $user->user_profile->company_website_url }}
                    </a>
                </p>
            </div>
            @unless(!$user->user_profile->company_address)
                <div class="col-xs-6 text-right">
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                    <address>
                        <strong>{{ $user->user_profile->company_name }}</strong>
                        <br>{{ $user->user_profile->company_address }}
                        <br>{{ $user->user_profile->city }},
                        {{ ($user->user_profile->state()->first() != '') ? $user->user_profile->state()->first()->abbreviation : ''}} {{ $user->user_profile->zipcode }}
                        <br>
                        {{--<abbr title="Phone">P:</abbr>(123) 456-7890--}}
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
                        </div>a
                    @else
                        <p>No new reviews.</p>
                    @endif
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.row  -->
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