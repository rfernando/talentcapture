@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1>
            My TalentGrams
            <small>Search Opportunities</small>
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

            <!-- /.col -->
            <div class="col-md-9">

                {{ flash_msg() }}

                @if(isset($subscription['type']) && !empty($subscription['type']))
                    <div class="alert alert-{{ $subscription['type'] }}">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ $subscription['msg'] }}
                    </div>
                @endif

                <div class="callout callout-info">
                    <h4><i class="fa fa-info"></i> Information</h4>

                    <p>To view details, please select a TalentGram from the list.</p>
                </div>
            </div>

        </div>
        <!-- /.row  -->
    </section>
@endsection