<div class="row">
    <div class="col-xs-12">
        @include('favourites._search')
    </div>
    <div class="col-xs-8">
        @include('jobs._agency_profile')
    </div>
    <div class="col-xs-4">
        {{--<a href="" class="btn btn-primary btn-block margin-bottom"></a>--}}

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Actions</h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="{{ base_url('messages/chat/'.$agency->id) }}" ><i class="fa fa-envelope-o"></i> &nbsp;Send a Message </a></li>
                    <li><a href="{{ base_url('messages/chat/'.$agency->id) }}"><i class="fa fa-comments-o"></i> Message History</a></li>
                    @if($isFavourite)
                        <li><a href="{{ base_url('agency/remove_favourite/'.$agency->id) }}"><i class="fa fa-thumbs-o-down"></i> Remove from Preferred</a></li>
                    @else
                        @if($agency->id!=get_user('id'))
                            <li><a href="{{ base_url('agency/add_favourite/'.$agency->id) }}"><i class="fa fa-thumbs-o-up"></i> Add to Preferred</a></li>
                        @endif
                    @endif
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /. box -->
        <!-- Changes -->

    </div>
</div>