
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
                        <input type="hidden" name="to_user_id" value="{{ $agency->id }}">
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
            {{ $agency->first_name." ".$agency->last_name }}

            @if($agency->type == 'agency')
                <span class="pull-right" style="font-size: 0.5em">
                    <input type="text" class="star-rating" data-size="xs" value="{{ $avgRating }}">
                </span>
            @endif

        </h3>
        <h5 class="widget-user-desc">{{($agency->user_profile->role != 'Other' ) ? $agency->user_profile->role.' @ ' : '' }} {{ $agency->user_profile->company_name }}</h5>
        {{-- @if(get_user('type') == 'agency') --}}

        {{-- @endif --}}
        <a href="{{ base_url('messages/chat/'.$agency->id) }}"  class="widget-user-contact-link pull-right"><i class="fa fa-envelope"></i> Send a Message</a>
    </div>
    <div class="widget-user-image">
        <img class="img-circle" src="{{ ($agency->profile_pic) ? base_url('public/uploads/'.$agency->profile_pic) :  base_url('public/img/default_profile_pic.jpg') }}" alt="User Avatar">
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
        <div class="row">
            {{--<div class="col-xs-6">
                <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                <p class="text-muted">
                    {{ $agency->email }}
                </p>
            </div>--}}

            <div class="col-xs-6">
                <strong><i class="fa fa-globe margin-r-5"></i> Website</strong>
                <p class="text-muted ">
                    <a href="{{ $agency->user_profile->company_website_url }}" target="_blank">
                        {{ $agency->user_profile->company_website_url }}
                    </a>
                </p>
            </div>


            <div class="col-xs-6" style="padding-left: 120px">
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
        @unless(!$agency->user_profile->company_address)
                <div class="col-xs-12">
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                    <address>
                        {{ $agency->user_profile->company_name }}
                        , {{ $agency->user_profile->company_address }}
                        , {{ $agency->user_profile->city }},
                        {{ ($agency->user_profile->state()->first() != '') ? $agency->user_profile->state()->first()->abbreviation : ''}} {{ $agency->user_profile->zipcode }}
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
                
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="description">
                    <p>{{ $agency->user_profile->company_desc }}</p>
                </div>
                
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