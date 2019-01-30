<div class="box box-success dashboard-box" style="border-top-color: #2e749c;">
    <div class="box-header">
        <h3 class="box-title" style="font-weight: 600;font-size: 24px;">Announcements and User Tips  </h3>
    </div>


    <div class="box-body"  style="overflow-y: auto; height: 250px; background-color: #ffffff">

        @if($latestBlogs->count())
            <div class="box-group" id="accordion">
                @foreach($latestBlogs as $blog)
                    <div class="panel box news-feeds">
                        <div class="box-header" >
                            <h4 class="box-title">
                                <div class="pull-right" >
                                    <a href="{{ base_url('blogs/view_detail/'.$blog->id) }}" target="_blank" style="color: #2e749c;">
                                        {{ $blog->blog_title }}
                                    </a>
                                </div>
                            </h4>
                            <span class="text-muted margin-r-5 date-alignment"><i class="fa fa-calendar margin-r-5"></i> {{ date('d M, Y', strtotime($blog->created_at ))}}</span>
                        </div>
                    </div>
                @endforeach

            </div>
        @else
            <p>There is no blog posts available.</p>
        @endif  
    </div>
</div>

@section('end-script')
    @parent

    <script type="application/javascript">

        

    </script>
@endsection