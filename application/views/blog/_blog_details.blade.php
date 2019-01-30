<div  id="candi_box" class="box box-primary" style="height:auto;border: none;">
    <div class="box-header with-border">
        <h3>
            {{ $blog->blog_title}}
             <span class="text-muted margin-r-5 date-size" style="margin-left: 15px;"><i class="fa fa-calendar margin-r-5"></i> {{ date('d M, Y', strtotime($blog->created_at ))}}</span>
        </h3>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {{ flash_msg() }}
        <div class="col-md-12" id="blog-details">
            {{ $blog->blog_desc }}
        </div>
    </div>
</div>
<!-- /. box -->


@section('end-script')
    @parent

    <script>
        function confirmclosed()
        {
            var msg = '<?php echo $msgvalue; ?>';
            var r = confirm(msg);
            if(r == false)
            {
                return false;
            }
        }
    </script>
    
    
    @if(!isset($popover))
    <script>
		$(document).ready(function()
        {
		 document.title="{{ $job->title }}";
        });
    </script>
    @endif

@endsection