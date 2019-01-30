@extends('template.template')

@section('main-content')
    <section class="content-header">
        <h1>
            <i class="fa fa-envelope-o"></i> Messages
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-envelope-o"></i> Messages</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row" id="main-content">
            <div class="col-md-3">
                @include('messages._contacts')
            </div>
            <div class="col-md-9">
                @if(isset($toUserID))
                    @include('messages._chat')
                @else
                    <div class="callout callout-info">
                        <h4>Messaging</h4>

                        <p>Please select a contact to view and chat with the contact</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection