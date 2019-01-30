@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1>
            My Jobs
            <small>Jobs added by You</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-briefcase"></i> Jobs</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row" id="main-content">

            <div class="col-md-3">
                @include('jobs._job_listing')
            </div>
            <!-- /.col -->

            <div class="col-md-3">
                @include('jobs._candidates')

                @include('jobs._accepted_agencies')
            </div>
            <!-- /.col -->

            <div class="col-md-6 ">
                @include('jobs._agency_profile')
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection