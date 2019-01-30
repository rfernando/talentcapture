@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1>
            My TalentGrams
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
                @include('agency._close_talentgrams')
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection
