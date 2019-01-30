@extends('admin.template._main')

@section('title','Job Details')@endsection


@section('main-content')
    <section class="content-header">
        <h1>
            Subscription Plan Details
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ admin_url('plans') }}"><i class="fa fa-briefcase"></i> Subscription Plan</a></li>
            <li class="active">View</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Profile Image -->
                <div class="box box-primary">
                    <!-- /.box-body -->
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Subscription Plan Details</h3>
                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <a class="label ajax {{($plan->status)  ? 'label-success' : 'label-default'}}" href="{{admin_url('plans/change_status/'.$plan->id)}}" data-success="change_status" data-toggle="tooltip" title="Click to Change">
                                    {{ ($plan->status) ? "Active" : "Inactive"}}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <dl id="job-details">

                            <dt>Subscription Plan Name</dt>
                            <dd>{{ $plan->plan_name }}</dd>

                            <dt>No Of Days</dt>
                            <dd>{{ $plan->no_of_days;}}

                            <dt>amount</dt>
                            <dd>{{ $plan->amount;}}</dd>

                            <dt>Description</dt>
                            <dd>{{ $plan->description;}}</dd>


                            <hr>
                        </dl>

                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection


@section('end-script')
    <script  type="text/javascript">
        var toggleDisplay = $('.toggle-display');
        toggleDisplay.on('click',function (e) {
            var text = toggleDisplay.text();
            $('#job-details').slideToggle(400, function () {
                if(text=='Show'){
                    toggleDisplay.text('Hide');
                }else{
                    toggleDisplay.text('Show');
                }
            });
        });
    </script>
@endsection