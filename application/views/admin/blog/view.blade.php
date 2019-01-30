@extends('admin.template._main')

@section('title','Blog Details')@endsection


@section('main-content')
    <section class="content-header">
        <h1>
            Email Template Details
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ admin_url('blogs') }}"><i class="fa fa-rss"></i> Blogs</a></li>
            <li class="active">{{ $blog->blog_title}}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <!-- /.box-body -->
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Blog Details</h3>
                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <a class="label ajax {{($blog->status)  ? 'label-success' : 'label-default'}}" href="{{admin_url('blogs/change_status/'.$blog->id)}}" data-success="change_status" data-toggle="tooltip" title="Click to Change">
                                    {{ ($blog->status) ? "Active" : "Inactive"}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <dl id="job-details">

                            <dt>Blog Title</dt>
                            <dd>{{ $blog->blog_title }}</dd>

                            <dl>
                                <dt style="margin-top: 10px">Blog Description </dt>
                                <dd>{{ $blog->blog_desc}}</dd>
                            </dl>


                            <hr>
                        </dl>


                    </div>
                </div>
                <!-- /.box -->
            </div>

        </div>
        <!-- /.row -->
    </section>
@endsection


@section('end-script')
    <script  type="text/javascript">
        var toggleDisplay = $('.toggle-display');
        toggleDisplay.on('click',function (e) {
            var text = toggleDisplay.text();
            $('#job-details').slideToggle(400, function () {
                if(text=='Show'){
                    toggleDisplay.text('Hide');
                }else{
                    toggleDisplay.text('Show');
                }
            });
        });
    </script>
@endsection