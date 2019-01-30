@extends('template.template')

@section('page-css')
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/flat/blue.css') }}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/morris/morris.css')  }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/datepicker/datepicker3.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/daterangepicker/daterangepicker-bs3.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    @endsection

    @section('main-content')
            <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            
            <small></small>
        </h1>
        <div class="col-lg-9 "></div>
        <div class="col-lg-3 padright0">
        <form action=""> <!-- adding search candidate form -->
            <div class="input-group">
                <input type="text" class="form-control input-lg search-input search-dashboard" autocomplete="off" id="search-user" placeholder="Search Candidates">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-primary btn-lg search-dashboard"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <ul class="nav nav-pills nav-stacked contacts-list search-list" id="search-results" style="display: none; height:auto">
        </ul>
        </div>
    </section>

    <!-- Main content -->
    <section class="content" id="main-content">
        {{--@include('dashboard_widgets._stats')--}}

        <!-- <div class="callout callout-info">
            <h4>NOTE</h4>

            <p>You can manage the widgets that are shown on your dashboard from your Profile Settings.</p>
        </div> -->

        <!-- Main row -->
        <div class="row">
            @if($subscription['dstatus'] == 'inactive')
            <section style="width:99%;padding-left:1%">
                <div class="alert alert-{{ $subscription['type'] }}">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ $subscription['msg'] }}
                </div>
            </section>
            @endif

            @if($subscription['dstatus'] == 'active')
                @if($subscription['type'] !="")
                <section style="width:99%;padding-left:1%">
                    <div class="alert alert-{{ $subscription['type'] }}">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ $subscription['msg'] }}
                    </div>
                </section>    
                @endif
            @endif

            <section class="col-lg-6 connectedSortable">

                @include('dashboard_widgets._my_jobs')
                @if( get_user('type')=='employer' && isset($platinum_users) && count($platinum_users)>0)
                    @include('dashboard_widgets._agency_showcase')
                @endif                

            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-6 connectedSortable">
                <div class="box box-warning dashboard-box" style="border-top-color: #2e749c;">
                    <div class="box-header">
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button type="button" class="btn btn-default btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <!-- /. tools  -->

                        <i class=""></i>

                        <h3 class="box-title" style="font-weight: 600;font-size: 24px;">My TalentGrams
                             <a rel="popover" data-toggle="collapse" data-html="true" data-placement="bottom" data-parent="#accordion" data-content_id="popover_ssos"><i class="glyphicon glyphicon-question-sign" ></i></a>
                        </h3>

                        <div id="popover_ssos" class="hide" style="width:90%">
                            <?php
                                $my_talentgrams = Site_messages::where('type','=','my_talentgrams')->first(); 
                                echo $my_talentgrams->msg; 
                            ?> 
                        </div>
                    </div>
                    <div class="box-body"  style="height: 492px;">
                        @include('dashboard_widgets._accepted_jobs')
                    </div>
                        <!-- /.box-body-->
                </div>

            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->

        <!-- Blog row -->
        <div class="row" style="height: 300px;margin-left: 2px;margin-right: 2px;">
            @include('dashboard_widgets._latest_blogs')
        </div>
        <!-- /.row (blog row) -->

    </section>
    <!-- /.content -->
@endsection

@section('page-js')
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{ admin_assets_url('plugins/morris/morris.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ admin_assets_url('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <!-- jvectormap -->
    <script src="{{ admin_assets_url('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ admin_assets_url('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ admin_assets_url('plugins/knob/jquery.knob.js') }}"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ admin_assets_url('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- datepicker -->
    <script src="{{ admin_assets_url('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ admin_assets_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>

    <!-- FastClick -->
    <script src="{{ admin_assets_url('plugins/fastclick/fastclick.js') }}"></script>
    <script>
        $('[rel="popover"]').popover({
            trigger: "hover",
            html:true,
            container: '#main-content',
            content: function() {
                return $('#'+$(this).data('content_id')).html();
            }
        });
    </script>
    <!-- adding script for the candidate search -->
    <script type="application/javascript">
        $(document).ready(function () {
            var searchResults = $('#search-results');
            $('#search-user').on('keyup', function () {
                var searchTxt = $.trim($(this).val());
                if(searchTxt.length > 0){
                    $.ajax({
                        url: '{{ base_url('agency/candidate_search').'/' }}' + searchTxt,
                        dataType : 'html',
                        success : function (result) {
                            searchResults.html(result);
                            searchResults.show();
                        }
                    });
                }else{
                    searchResults.hide();
                }
            });

            searchResults.slimscroll({
                height: 'auto'
            }).css("width", "100%").css("overflow", "visible");

            $(".search-list").css("height", 'auto');
            $(".slimScrollDiv").css("overflow", 'visible').css("height", 'auto');
        });
    </script>
@endsection

@section('end-script')
    <script src="{{ admin_assets_url('dist/js/pages/dashboard.js') }}"></script>

    <script type="text/javascript">
        var body = $('body');

        function callback(result, element){
            element.closest('li').fadeOut();
            var type = 'Error';
            if(result.status)
                type = 'Success';
            var className = (type == 'Error') ? 'danger' : 'success';
            var html = '<div class="alert alert-'+ className +'">'+ result.msg +'</div>';
            $('#rsvp-result-container').html(html).slideDown(500).delay(2000).slideUp(500);
        }

        $('[rel="popover"]').popover({
            trigger: "hover",
            html:true,
            container: '#main-content',
            content: function() {
                return $('#'+$(this).data('content_id')).html();
            }
        });

        // hide candidate message after 5 secs
    </script>
@endsection
