<div class="box-comment">
    @if($ratings->profile_pic)
        <img class="img-circle img-sm"  src="{{ base_url('public/uploads/'.$ratings->profile_pic) }}"  alt="user">
    @else
        <img class="img-circle img-sm"  src="{{ base_url('public/img/default_profile_pic.jpg') }}" alt="user">
    @endif
    <div class="comment-text">
        <span class="username">{{ $ratings->first_name." ".$ratings->last_name }}
            @for($i=5; $i >= 1; $i --)
                <i class="fa fa-star {{ ($i <= $ratings->pivot->rating ) ? 'text-yellow' : 'text-muted' }} pull-right"></i>
            @endfor
        </span>

        <!-- Getting the company name -->
        <?php 
        $useragency = User::with('user_profile')->find($ratings->id);

        ?>
        <span class="username">{{$useragency->user_profile->company_name}}</span>
        {{ $ratings->pivot->review }}
    </div>
</div>