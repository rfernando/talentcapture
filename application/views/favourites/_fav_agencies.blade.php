<div class="box box-solid">
    <div class="box-body {{ ($count  = $agencies->count()) ? 'no-padding' : '' }}">
        @if($count)
        <ul class="nav nav-pills nav-stacked contacts-list ">
            @foreach($agencies as $c)
                <li class="{{ (isset($agency->id) && $agency->id == $c->id) ? 'active' : '' }}">
                    <a href="{{ base_url('agency/detail/'.$c->id) }}">
                        @if($profileImg = $c->profile_pic)
                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                        @else
                            <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                        @endif
                        <div class="contacts-list-info">
                            <span class="contacts-list-name" style="font-weight: 600">
                                {{ $c->first_name. " " . $c->last_name }}
                                {{--<small class="contacts-list-date pull-right">2/28/2015</small>--}}
                            </span>
                            <span class="contacts-list-msg">
                                {{ $c->user_profile->company_name }}
                            </span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
        @else
            <p>There are no agency users to display at this time. To send a TalentGram to a specific agency user, add the agency user to your Preferred list.</p>
        @endif
    </div>
</div>