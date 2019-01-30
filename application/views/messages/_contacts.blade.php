<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><?php if($type == 'agency'){echo 'Contacts';}else{echo 'Recruiter Contacts';}?></h3>

        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <br><br>
        <input type="text" class="form-control search-input" autocomplete="off" id="search-contacts" placeholder="Search contacts...">
        <ul class="nav nav-pills nav-stacked contacts-list search-list" id="search-results" style="display: none; height:auto">

        </ul>

    </div>
    <div class="box-body no-padding"  style="overflow-y: auto; height: 450px;">
        <ul class="nav nav-pills nav-stacked contacts-list no-padding">
            @if(isset($toUserID) && !in_array($toUserID,array_keys($contacts)))
                <li class="new-active">
                    <a href="{{ base_url('messages/chat/'.$toUserID)  }}">
                        @if($profileImg = $toUser->profile_pic)
                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                        @else
                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                        @endif
                        <div class="contacts-list-info">
                            <span class="contacts-list-name">
                                {{ $toUser->first_name." ".$toUser->last_name }}
                                <span class="label label-primary pull-right"></span>
                            </span>
                            <span class="contacts-list-msg">--</span>
                        </div>
                    </a>
                </li>
            @endif

            @foreach($contacts as $c)
                <span class="hidden">{{ $contact = ($c['to_user_id'] == get_user('id')) ? "from_user" : "to_user" }}-- {{ $toUserID }} -- {{ $c[$contact]['id'] }}</span>
                <?php if($c[$contact]['type']!='admin') { ?>
                    <li class="{{ (isset($toUserID) && $toUserID == $c[$contact]['id']) ? 'new-active' : '' }}">
                        <a href="{{ base_url('messages/chat/'.$c[$contact]['id'])  }}">
                            @if($profileImg = $c[$contact]['profile_pic'])
                                <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                            @else
                                <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                            @endif
                            <div class="contacts-list-info">
                                <span class="contacts-list-name" <?=($c['unread']) ? 'style="font-weight:800;"' : '';?>>
                                    {{ $c[$contact]['first_name']. " " . $c[$contact]['last_name'] }} 
                                    <span class="label label-primary pull-right">{{ ($c['unread']) ? $c['unread'] : '' }}</span>
                                    {{--<small class="contacts-list-date pull-right">2/28/2015</small>--}}
                                </span>
                                <span class="contacts-list-msg">
                                    {{-- $c['text'] --}}
                                </span>
                            </div>
                        </a>
                    </li>
                <?php } ?>
            @endforeach
            <!-- Admin Supprt Always In End -->
            @foreach($contacts as $c)
            <span class="hidden">{{ $contact = ($c['to_user_id'] == get_user('id')) ? "from_user" : "to_user" }}-- {{ $toUserID }} -- {{ $c[$contact]['id'] }}</span>
                <?php if($c[$contact]['type']=='admin' && $c[$contact]['id']==1) { ?>
                    <li class="{{ (isset($toUserID) && $toUserID == $c[$contact]['id']) ? 'new-active' : '' }}">
                        <a href="{{ base_url('messages/chat/'.$c[$contact]['id'])  }}">
                            @if($profileImg = $c[$contact]['profile_pic'])
                                <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                            @else
                                <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                            @endif
                            <div class="contacts-list-info">
                                <span class="contacts-list-name"  <?=($c['unread']) ? 'style="font-weight:800;"' : '';?>>
                                    {{ $c[$contact]['first_name']. " " . $c[$contact]['last_name'] }} 
                                    <span class="label label-primary pull-right">{{ ($c['unread']) ? $c['unread'] : '' }}</span>
                                    {{--<small class="contacts-list-date pull-right">2/28/2015</small>--}}
                                </span>
                                <span class="contacts-list-msg">
                                    {{-- $c['text'] --}}
                                </span>
                            </div>
                        </a>
                    </li>
                <?php continue; } ?>
            @endforeach
        </ul>
    </div>
</div>

@section('end-script')
    @parent

    <script type="application/javascript">
        $(document).ready(function () {
            var searchResults = $('#search-results');
            $('#search-contacts').on('keyup', function () {
                var searchTxt = $.trim($(this).val());
                if(searchTxt.length > 0){
                    $.ajax({
                        url: '{{ base_url('messages/search').'/' }}' + searchTxt,
                        dataType : 'html',
                        success : function (result) {
                            searchResults.html(result);
                            searchResults.show();
                        }
                    });
                }else{
                    searchResults.hide();
                }
            });

            searchResults.slimscroll({
                height: 'auto'
            }).css("width", "100%").css("overflow", "visible");

            $(".search-list").css("height", 'auto');
            $(".slimScrollDiv").css("overflow", 'visible').css("height", 'auto');
        });
    </script>
@endsection