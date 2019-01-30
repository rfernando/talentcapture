@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1>
            My Searches
            <small>Your Saved Jobs</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-search"></i> My Searches</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                @include('agency._job_listing')
            </div>

            <div class="col-md-3">
                @include('agency._candidates')

                @include('agency._job_owned_by')
            </div>


            <div class="col-md-6">
                @include('agency._owner_details')
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection


@section('page-js')
    <script src="{{ admin_assets_url('plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
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