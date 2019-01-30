@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1></h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-users"></i> Employer</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" style="margin-top: 20px;">
        <div class="row" id="main-content">
            <div class="col-md-12">
                @include($page)
            </div>
        </div>
    </section>
@endsection

<!-- Chnages -->
    <?php $count = $agencies->count(); ?>
    <?php if(($page == 'favourites._search') && ($count > 0)){
        header("Location: ".base_url('agency/detail/'.$agencies[0]->id));
    } ?>