@extends('admin.template._main')

@section('title','Users list')@endsection

@section('page-css')
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">

@endsection

@section('main-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Email Templates Vaibales List
            <small> List of Varables in Email Templates added by Admin</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><i class="fa fa-envelope"></i> Email Templates</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">List of Email Templates Varables List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul class="row">
                            <li class="col-md-3">
                            
                                <strong>{{ get_email_template_name(1) }}</strong>
                                <!--<strong>CANDIDATE HIRED</strong>-->
                                <ul>
                                    <li>$CANDIDATENAME$</li>
                                    <li>$STARTDATE$</li>
                                    <li>$BASESALARY$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@ - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(2) }}</strong>
                                <ul>
                                    <li>$CANDIDATENAME$</li>
                                    <li>$STARTDATE$</li>
                                    <li>$BASESALARY$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(3) }}</strong>
                                <ul>
                                    <li>$CANDIDATENAME$</li>
                                    <li>$FIRSTNAME$</li>
                                    <li>$LASTNAME$</li>
                                    <li>$STARTDATE$</li>
                                    <li>$BASESALARY$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(4) }}</strong>
                                <ul>
                                    <li>$FIRSTNAME$</li>
                                    <li>$LASTNAME$</li>
                                    <li>$TYPE$</li>
                                    <li>$TITLE$</li>
                                    <li>$SALARY$</li>
                                    <li>$POSITIONTYPE$</li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="row">
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(5) }}</strong>
                                <ul>
                                    <li>$CANDIDATENAME$</li>
                                    <li>$JOBTITLE$</li>
                                    <li>$FIRSTNAME$</li>
                                    <li>$LASTNAME$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(6) }}</strong>
                                <ul>
                                    <li>$COMPANYNAME$</li>
                                    <li>$CANDIDATENAME$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(7) }}</strong>
                                <ul>
                                    <li>$JOBTITLE$</li>
                                    <li>$CANDIDATENAME$</li>
                                    <li>$EMPLOYEERFEEDBACK$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li><!-- -->
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(8) }}</strong>
                                <ul>
                                    <li>$COMPANYNAME$ </li>
                                    <li>$JOBTITLE$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="row">
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(9) }}</strong>
                                <ul>
                                    <li>@RESETLINK@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(10) }}</strong>
                                <ul>
                                    <li>$COMPANYNAME$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(11) }}</strong>
                                <ul>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(12) }}</strong>
                                <ul>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="row">
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(13) }}</strong>
                                <ul>
                                    <li>$COMPANYNAME$</li>
                                    <li>$CANDIDATENAME$</li>
                                    <li>$STARTDATE$</li>
                                    <li>$SALARY$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(14) }}</strong>
                                <ul>
                                    <li>$JOBTITLE$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(15) }}</strong>
                                <ul>
                                    <li>$JOBTITLE$</li>
                                    <li>$CANDIDATENAME$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(16) }}</strong>
                                <ul>
                                    <li>$FIRSTNAME$</li>
                                    <li>$LASTNAME$</li>
                                    <li>$COMPANYNAME$</li>
                                    <li>$USERTYPE$</li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="row">
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(17) }}</strong>
                                <ul>
                                    <li>$STARTDATE$</li>
                                    <li>$SALARY$</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(18) }}</strong>
                                <ul>
                                    <li>$FIRSTNAME$</li>
                                    <li>$LASTNAME$</li>
                                    <li>$TEXT$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(19) }}</strong>
                                <ul>
                                    <li>$AGENCYNAME$</li>
                                    <li>$JOBNAME$</li>
                                    <li>@SITEURL@</li>
                                </ul>
                            </li>
                            <li class="col-md-3">
                                <strong>{{ get_email_template_name(20) }}</strong>
                                <ul>
                                    <li>$JOBNAME$</li>
                                    <li>@SITEURL@</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="row">
                              <li class="col-md-3">
                                <strong>{{ get_email_template_name(21) }}</strong>
                                <ul>
                                    <li>$JOBNAME$</li>
                                    <li>!@  - ancher tag end</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('page-js')
    <!-- DataTables -->
    <script src="{{ admin_assets_url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ admin_assets_url('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ admin_assets_url('plugins/iCheck/icheck.min.js') }}"></script>



    <script>
        $(function () {
            $('#example2').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "order": [],
                "columnDefs": [
                    {"width": "1%", "targets": 0},
                    {"targets": [0, 5], "orderable": false, "searchable": false}
                ]
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection
