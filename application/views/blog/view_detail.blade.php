@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1>
            <p></p>

        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ base_url('blogs') }}"><i class="fa fa-rss"></i> Announcements and User Tips</a></li>
            
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row" id="main-content">
            <div class="col-md-12 no-padding no-margin">
                @include('blog._blog_details')
            </div>
            <!-- /.col -->

            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection


@section('page-js')
    <!-- Select2 -->
    <script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>
    <!-- Datepicker -->
    <script src="{{ admin_assets_url('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('end-script')
    <script  type="text/javascript">
        var toggleDisplay = $('.toggle-display');
        toggleDisplay.on('click',function (e) {
            var text = toggleDisplay.text();
            $('#job-details').slideToggle(400, function () {
                if(text=='Show'){
                    $('#job-actions').removeClass('col-md-12').addClass('col-md-2');
                    toggleDisplay.text('Hide');
                }else{
                    $('#job-actions').removeClass('col-md-2').addClass('col-md-12');
                    toggleDisplay.text('Show');
                }
            });
        });
    </script>
@endsection