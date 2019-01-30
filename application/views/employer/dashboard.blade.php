@extends('template.template')

@section('main-content')
    <!-- Content Header (Page header) -->
    <section class="content-header" id="main-content">
        <h1>
            
            <small></small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        {{--@include('dashboard_widgets._stats')--}}

        {{--<div class="callout" style="background-color:#ffffff;border-left: 3px solid #ffffff;box-shadow: 0 0 10px grey;">
            <p style="color:black;"> The dashboard shows your open jobs and any associated candidates submitted by Agencies. Click on a candidate name to view the candidate profile. To view your job listing details, click on My Jobs from the menu.</p>
        </div>--}}

        <div class="row">
            <section class="col-lg-6 connectedSortable">
                @include('dashboard_widgets._my_jobs')
                

            </section>

            <section class="col-lg-6 connectedSortable">
                    @include('dashboard_widgets._agency_showcase')
            </section>

            <!-- quick email widget -->
        </div>
        
        <!-- Blog row -->
        <div class="row" style="height: 300px;margin-left: 2px;margin-right: 2px;">
            @include('dashboard_widgets._latest_blogs')
        </div>
        <!-- /.row (blog row) -->

    </section>
    <!-- /.content -->
@endsection


@section('end-script')

    <script type="text/javascript">
        $('[rel="popover"]').popover({
            trigger: "hover",
            html:true,
            container: '#main-content',
            content: function() {
                return $('#'+$(this).data('content_id')).html();
            }
        });
    </script>
@endsection