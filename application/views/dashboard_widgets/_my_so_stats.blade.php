<div class="box box-warning">
    <div class="box-header">
        <!-- tools box -->
        <div class="pull-right box-tools">
            <button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                <i class="fa fa-plus"></i>
            </button>

        </div>
        <!-- /. tools -->

        <i class="fa fa-list"></i>

        <h3 class="box-title">Performance Summary</h3>
    </div>
    <div class="box-body">
        <div class="box-group" id="accordion_agency">
            <div class="panel box">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-html="true" href="#my_job_list" data-parent="#accordion_agency">
                            My Jobs
                        </a>
                    </h4>
                </div>
                <div class="box-body panel-collapse collapse" id="my_job_list">
                    <ul class="nav nav-pills nav-stacked">
                        @foreach($stats_job as $key => $val)
                            <li>
                                <div class="pull-right">
                                    <span class="pull-right" style="width: 31px;font-weight: 700;">{{ $val }}</span>
                                </div>
                                <h5>
                                    <span style="width: 31px;font-weight: 700;">
                                        {{ ucwords(str_replace('so','SO\'s',str_replace('_',' ',$key))) }}
                                    </span>
                                </h5>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @if(isset($stats_so))
        <div class="box-group" id="accordion_employer">
            <div class="panel box">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-html="true" href="#so_job_list" data-parent="#accordion_employer">
                            My TalentGrams
                        </a>
                    </h4>
                </div>
                <div class="box-body panel-collapse collapse" id="so_job_list">
                    <ul class="nav nav-pills nav-stacked">
                            @foreach($stats_so as $key => $val)
                                <li>
                                    <div class="pull-right">
                                        <span class="pull-right" style="width: 31px;font-weight: 700;">{{ $val }}</span>
                                    </div>
                                    <h5>
                                        <span style="width: 31px;font-weight: 700;">
                                            {{ ucwords(str_replace('so','SO\'s',str_replace('_',' ',$key))) }}
                                        </span>
                                    </h5>
                                </li>
                            @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- /.box-body-->
</div>
