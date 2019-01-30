@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1>
            Announcements and User Tips           
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-rss"></i> Announcements and User Tips</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row" id="main-content">
            <div class="box-body" style="overflow-y: auto; height: 720px; background-color: #ffffff">
                @foreach($blogs as $blog)
                    <div class="panel box" style="border: none; box-shadow: none;height: 15px;">
                        <div class="box-header">
                            <h4 class="box-title">
                                <div class="pull-right" >
                                    <a href="{{ base_url('blogs/view_detail/'.$blog->id) }}" target="_blank">
                                        {{ $blog->blog_title }}
                                    </a>
                                </div>
                            </h4>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection