@extends('admin.template._main')

@section('title','Free Trial')@endsection

@section('page-css')
        <!-- jvectormap -->
<link rel="stylesheet" href="{{ admin_assets_url('plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">
<link rel="stylesheet" href="{{ admin_assets_url('plugins/select2/select2.min.css') }}">


@endsection

@section('main-content')
    <section class="content-header">
        <h1>
            Free Trial
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ admin_url('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Free Trial</li>
        </ol>
    </section>


     <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-8">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-sitemap"></i> List of Users and their Free trials </h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {{ flash_msg() }}
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width:50%">Agent Name</th>
                                <th>Free Trail Periods (In Days)</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($traildetails as $data)
                                <tr>
                                    <?php if($data->agency_id !="0"){ $userlist= User::where('id','=',$data->agency_id)->first(); } ?>
                                    <td style="width:50%" class="title_val">@if($data->agency_id !="0"){{ $userlist->first_name }} {{ $userlist->last_name }} @else All Agency @endif</td>
                                    
                                    <td class="center">{{ $data->no_of_days }}</td>
                                    
                                    
                                    <td>@if($data->agency_id !="0")<a href="{{ admin_url('plans/deletetrial/'.$data->id)}}" class="btn btn-danger" onclick="return confirm('Do you really want to delete this record ?')"><i class="fa fa-trash"></i></a>@endif</td>
                                </tr>
                            @endforeach
                            

                            </tbody>
                            
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-plus-circle"></i>  New Trial Period</h3>
                    </div>
                    <form role="form" action="{{ admin_url('plans/addfreetrial') }}" method="POST" onsubmit="return validate_form()">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Select Agency</label>
                                <select class="select2 form-control" name="agency_id" id="agency">
                                    @foreach($agencyOptions as $opt)
                                        <option value="{{ $opt->id }}">{{ $opt->first_name }} {{ $opt->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Trail Period (In Days)</label>
                                <input class="form-control" id="period" name="no_of_days" onblur="validate_form()" placeholder="Enter Period" autocomplete="off" type="text">
                            </div>

                            <div class="form-group">
                                <label>Subscription Plan</label>
                                
                                <select name="plan_id" class="select2 form-control" id="subscription_plan_add">
                                    <option value="1" >Split Fee Network : $75.00 USD - monthly</option>
                                    <option value="2">Split Fee Network + Dashboard Advertising : $150.00 USD - monthly</option>
                                </select>

                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right" >Save</button>
                        </div>
                    </form>
                </div>
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-plus-circle"></i> Update Trial Period</h3>
                    </div>
                    <form role="form" action="{{ admin_url('plans/edittrial') }}" id="edit-form" method="POST" onsubmit="return validate_updateform()">
                        <div class="box-body">
                            <div class="form-group">
                                <label> Select Agency</label><br>
                                <select class="select2 form-control" name="agency_id" id="agency_id">
                                   <option value="0">All Agency</option>
                                   @foreach($traildetails as $data)
										<?php 
										if($data->agency_id !="0")
										{ 
											$userlist= User::where('id','=',$data->agency_id)->first(); 
											?>
											<option value="{{$data->agency_id}}">{{ $userlist->first_name }} {{ $userlist->last_name }}</option>
											<?php
										} 
										?>
                            		@endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Trial Period (In Days)</label>
                                <input class="form-control" id="perioddays" name="no_of_days" onblur="validate_updateform()" placeholder="Enter No of Days" autocomplete="off" type="text">
                            </div>

                            <div class="form-group">
                                <label>Subscription Plan</label>
                                
                                <select name="plan_id" class="select2 form-control" id="subscription_plan_upd">
                                    <option value="1" >Split Fee Network : $75.00 USD - monthly</option>
                                    <option value="2">Split Fee Network + Dashboard Advertising : $150.00 USD - monthly</option>
                                </select>

                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right" >Update</button>
                        </div>
                    </form>
                </div>
            </div>

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
    <script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>



    <script>
        $(function () {

            $('#example2').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "order": [[ 2, "asc" ]],
                "columnDefs": [
                    { "width": "1%", "targets": 0 },
                    {"targets": [1,1], "orderable": false, "searchable": false }
                ]
            });

            $('#example2').on('click','.edit-category',function () {
                $('#edit-form').attr('action', $(this).data('url'));
                $('#edit-title').val($(this).closest('tr').find('td.title_val').text());
                var parentIds = $(this).closest('tr').find('td.parent_id_val').data('parent_id');
                var parentIdCount = $(this).closest('tr').find('td.parent_id_val').data('count');
                if(parentIdCount == '1'){
                    $editParentIdField.val(parentIds).trigger("change");
                }else{
                    $editParentIdField.val(parentIds.split(', ')).trigger("change");
                }

            });
        });

        function validate_form(){
            var title = $('#period');
            var industryOptions = $('#agency');
            var titleValue = $.trim(title.val());
            var industryValue = industryOptions.val();
            if(!titleValue){
                title.closest('.form-group').removeClass('has-success');
                title.closest('.form-group').addClass('has-error');
            }else{
                title.closest('.form-group').removeClass('has-error');
                title.closest('.form-group').addClass('has-success');
            }

            if(!industryValue){
                industryOptions.closest('.form-group').removeClass('has-success');
                industryOptions.closest('.form-group').addClass('has-error');
            }else{
                industryOptions.closest('.form-group').removeClass('has-error');
                industryOptions.closest('.form-group').addClass('has-success');
            }
            return (titleValue && industryValue) ? true : false;
        }

        function validate_updateform(){
            var title = $('#perioddays');
            var industryOptions = $('#agency_id');
            var titleValue = $.trim(title.val());
            var industryValue = industryOptions.val();
            if(!titleValue){
                title.closest('.form-group').removeClass('has-success');
                title.closest('.form-group').addClass('has-error');
            }else{
                title.closest('.form-group').removeClass('has-error');
                title.closest('.form-group').addClass('has-success');
            }

            if(!industryValue){
                industryOptions.closest('.form-group').removeClass('has-success');
                industryOptions.closest('.form-group').addClass('has-error');
            }else{
                industryOptions.closest('.form-group').removeClass('has-error');
                industryOptions.closest('.form-group').addClass('has-success');
            }
            return (titleValue && industryValue) ? true : false;
        }

        
    </script>
@endsection