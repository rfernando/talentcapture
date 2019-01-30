@section('page-css')
<link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">
@endsection
<div class="box box-success" id="active">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-briefcase"></i> Subscription Plans</h3>
        <div class="box-tools">
            <!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>-->
        </div>
    </div>
    <div class="box-body {{ ($subscription_plans->count()) ? 'no-padding' : ''  }}">
        @if($subscription_plans->count())
            <ul class="nav nav-pills nav-stacked">
                @foreach($subscription_plans as $subscription_plan)
                    <li class="plan-list row">
                        <div class="col-md-12" style="margin-top: 12px;margin-bottom: 12px;">
                            <div class="col-md-2">
                                <input type="radio" name="plan_id" value="{{ $subscription_plan->id }}" class="plan-select" onclick="changes('1')">

                            </div>
                            <div class="col-md-9">
                                <a class="label label-success" style="font-size: 16px;">{{ $subscription_plan->plan_name }}</a>
                                <a class="label label-primary" style="font-size: 16px;"><i class="fa fa-clock-o"></i> {{ $subscription_plan->no_of_days}}</a>
                                <a class="label label-danger" style="font-size: 16px;"><i class="fa fa-dollar"></i> {{ $subscription_plan->amount  }}</a>
                                @if($subscription_plan->description)
                                    <a href="javascript:void(0)"  style="font-size: 16px; margin-left: 10px;" data-toggle="popover" data-trigger="hover"  data-content="<p>{{ $subscription_plan->description  }}</p>" data-html="true" style="margin-left: 10px;"><i class="fa fa-sticky-note" ></i></a>
                                @endif
                            </div>
                            {{-- "<div class="col-md-9"> Name : ".$subscription_plan->plan_name."<br> Days : ".$subscription_plan->no_of_days."<br> Amount  : ".$subscription_plan->amount."</div>"  --}}
                        </div>

                    </li>
                @endforeach
            </ul>
       @endif

    </div>
    <!-- /.box-body -->
</div>


@section('page-js')
        <!-- DataTables -->
<script src="{{ admin_assets_url('plugins/iCheck/icheck.min.js') }}"></script>


<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });




</script>

@endsection


