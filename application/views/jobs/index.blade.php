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

            <div class="col-md-9 no-padding">
                {{ flash_msg(); }}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Job listings</h3>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <p class="text-center">Select a Job from the list of active Jobs to view Details <br>OR<br> <a href="{{base_url('jobs/add_new') }}">Create a new Job</a></p>

                    </div>
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection