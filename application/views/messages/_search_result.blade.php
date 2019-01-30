@if($usersList->count())
    @foreach($usersList as $agency)
        <li>
            <a href="{{ base_url("messages/chat/$agency->id") }}">
                @if($profileImg = $agency->profile_pic)
                    <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/uploads/'.$profileImg) }}">
                @else
                    <img class="contacts-list-img" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                @endif
                <div class="contacts-list-info">
                    <span class="contacts-list-name">{{ $agency->first_name }} {{ $agency->last_name }} </span>
                </div>
            </a>
        </li>
    @endforeach
@else
    <li>
        <a href="javascript:void(0)">Sorry, No Results Found</a>
    </li>
@endif