@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1>My TalentGrams
            <?php /*?>My Searches
            <small>Your Saved Jobs</small><?php */?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-search"></i> My Searches</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row" id="main-content">
            <div class="col-md-3">
                @include('agency._job_listing')
                @include('agency._candidates')
            </div>

            <div class="col-md-9 no-padding no-margin">
                @include('agency._candidate_detail')
            </div>
            <!-- /.col

            <div class="col-md-6 ">
                
            </div>
            /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection