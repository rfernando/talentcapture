
@section('page-css')
    @parent
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/select2/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/datepicker/datepicker3.css') }}">
    <!--Bootstrap Star Rating-->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">
@endsection

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $page_name }} - {{ $job->title }}</h3>
    </div>
    <div class="box-body">
        <form action="{{ ($page_name == 'Hire Candidates') ? base_url('jobs/hire_candidates/'. $job->id) : base_url('jobs/close/'. $job->id) }}" method="post" class="validateForm">
            <ul class="payment-wizard">
                <li class="active">
                    <div class="wizard-heading">
                        1. Hired Candidates
                        <span class="fa fa-users"></span>
                    </div>
                    <div class="wizard-content">
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="candidate-hired" id="no-candidate-hired" value="0" {{ ($candidateHiredCount = $job->candidates()->where("hired",1)->count()) ? 'checked' : '' }}>
                                     &nbsp;&nbsp;I did not hire a candidate through the TalentCapture Portal
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="candidate-hired" {{ (!count($candidates_list)) ? 'disabled title="No candidates on this Job"' : ($candidateHiredCount) ? 'checked' : '' }} id="candidates-hired" value="1">
                                    &nbsp;&nbsp;The following candidates were successfully hired.
                                </label>
                            </div>
                        </div>

                        <div class="form-group" >
                            <label for="hired-candidates">Select Candidates to Hire</label>
                            {{ form_dropdown('hired-candidates[]', $candidates_list, $job->candidates()->where("hired",1)->lists('id'), 'id="hired-candidates" class="form-control select2" multiple') }}

                        </div>
                        <label for="hired-candidates" id="hired-candidates-label" style="display: none;">Hired Candidates</label>
                        <ul id="hired-candidates-container">
                            @each('jobs._hired_candidates', $hiredCandidates = $job->candidates()->where("hired",1)->get(), 'hired_candidate')
                        </ul>
                        <input type="hidden" id="hiredCandidates" value='{{ htmlspecialchars($hiredCandidates->toJson() , ENT_QUOTES, 'UTF-8')  }}'>
                        <button class="btn-green done" type="{{ (count($agencies)) ? 'button' : 'submit' }}">
                            {{ (count($agencies)) ? 'Continue' : 'Close Job' }}
                        </button>
                    </div>
                </li>
                @if(count($agencies))
                    <li>
                        <div class="wizard-heading">
                            2. Rate Agency Recruiters
                            <span class="fa fa-star"></span>
                        </div>
                        <div class="wizard-content">
                            <div class="row row-fluid agency-rating-container">
                                @foreach($agencies as $agency)
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="info-box">
                                            <div class="info-box-icon">
                                                <img class="img-circle img-thumbnail" src="{{ ($agency['profile_pic']) ? base_url('public/uploads/'.$agency['profile_pic']) :  base_url('public/img/default_profile_pic.jpg') }}" alt="User Avatar">
                                            </div>

                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $agency->user_profile->company_name }}</span>
                                                <input name="agency_rating[{{ $agency->id }}][rating]" type="text" class="star-rating" data-size="xs" value="{{ (isset($agencies_ratings[$agency->id])) ? $agencies_ratings[$agency->id] : 0 }}">
                                            </div>
                                            <!-- /.info-box-content -->
                                            <div class="favourite-data">
                                                <label class="add-to-favourite pull-right {{ in_array($agency->id,$favourite_agencies) ? 'active' : '' }}">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-check fa-stack-1x fa-inverse"  title="Add to preferred " data-toggle="tooltip"></i>
                                                    </span>
                                                    <input type="checkbox" class="hidden" name="favourite_agencies[]" value="{{ $agency->id }}" {{ in_array($agency->id,$favourite_agencies) ? 'checked="checked"' : '' }}>
                                                </label>
                                                <a class="write-a-review pull-right">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"  title="Write a Review" data-toggle="tooltip"></i>
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="review-box">
                                                <textarea name="agency_rating[{{ $agency->id    }}][review]" class="form-control review" placeholder="Write a Review" style="display: none;"></textarea>
                                            </div>
                                        </div><!-- /.info-box -->
                                    </div>
                                @endforeach
                            </div>
                            <button class="btn-green done" type="submit">{{ $page_name }}</button>
                        </div>
                    </li>
                @endif
            </ul>
        </form>
    </div>
</div>


@section('page-js')
    @parent
    <!-- Select2 -->
    <script src="{{ admin_assets_url('plugins/select2/select2.full.min.js') }} "></script>
    <!-- Datepicker -->
    <script src="{{ admin_assets_url('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!--Bootstrap Star Rating-->
    <script src="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ admin_assets_url('plugins/iCheck/icheck.min.js') }}"></script>
@endsection


@section('end-script')
    @parent

    <script type="application/javascript">
        $('document').ready(function(){
            var isDisabled = $('#no-candidate-hired').prop('checked');
            $('.select2').select2({
                width: '100%',
                placeholder : 'Select Hired Candidates',
                minimumResultsForSearch: 10,
                disabled : isDisabled
            });

            $(".star-rating").rating({
                "size":"xs",
                "clearButton" : '',
                "showCaption" : false
            });

            $('label.add-to-favourite :checkbox').change(function(){
                $(this).parent().toggleClass('active');
            });


            $('.write-a-review').on('click',function (e) {
                var reviewBox = $(this).closest('.favourite-data').next().find('.review');
                var closed = (reviewBox.css('display') == 'none') ? 1 : 0;
                reviewBox.slideToggle();
                var infoBox = $(this).closest('.info-box');
                if(closed)
                    infoBox.animate({'border-bottom-left-radius': '4px'});
                else
                    infoBox.animate({'border-bottom-left-radius': '45px'});
            });

            $('.datepicker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                startDate: '+1w'
            });
        });

        var body = $('body');
        body.on('change','input[name="candidate-hired"]', function() {
            var value = parseInt($(this).val());
            if(value){
                $("#hired-candidates").prop("disabled", false);
                $('#hired-candidates-container').show();
                $('#close-without-hiring').hide();
            }else{
                $("#hired-candidates").prop("disabled", true);
                $('#hired-candidates-container').hide();
                $('#close-without-hiring').show();
            }
        });

        var hiredCandidates = JSON.parse($('#hiredCandidates').val());
        console.log(hiredCandidates);
        body.on('select2:select','.select2', function (evt) {
            var html  = '';
            $($(this).select2("data")).each(function (key, candidate) {
                var hireDetailsExist = typeof hiredCandidates[key] != 'undefined';
                if(!hireDetailsExist){
                    hiredCandidates[key] = {start_date : '', base_salary : '', additional_info : ''}
                }
                html += '<li>' +
                        '<label>'+ candidate.text +'</label>' +
                        '<input type="hidden" name="hired_candidates['+key+'][candidate_id]" class="form-control" value="'+ candidate.id +'">' +
                        '<div class="row">'+
                        '<div class="col-xs-6">'+
                        '<div class="input-group">' +
                        '<span class="input-group-addon"><i class="fa fa-calendar"></i></span>' +
                        '<input type="text" name="hired_candidates['+key+'][start_date]" class="form-control datepicker" placeholder="Start Date" data-required="true" value="' + hiredCandidates[key].start_date + '">' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-xs-6">' +
                        '<div class="input-group">'+
                        '<span class="input-group-addon"><i class="fa fa-dollar"></i></span>'+
                        '<input type="text" name="hired_candidates['+key+'][base_salary]" class="form-control" placeholder="Base Salary" data-required="true" value="' + hiredCandidates[key].base_salary  + '">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<br>' +
                        '<div class="row">' +
                        '<div class="col-xs-12">'+
                        '<textarea class="form-control" name="hired_candidates['+key+'][additional_info]"  placeholder="Additional Details" data-required="true">' + hiredCandidates[key].additional_info +'</textarea>' +
                        '</div>' +
                        '</div>' +
                        '<hr>'+
                        '</li>';
            });
            $('#hired-candidates-container').html(html);
            $('#hired-candidates-label').show();
            //Date picker
            $('.datepicker').datepicker({
                autoclose: true
            });
        });

    </script>
    <!--- Start Display Active and Inactive Jobs's List--->
    @if($job->closed)
        <script>
            $('#active').hide();
        </script>
    @else
        <script>
            $('#closed').hide();
        </script>
    @endif
    <!--- End Display Active and Inactive Jobs's List--->

@endsection



