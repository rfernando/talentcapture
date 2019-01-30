<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Talent Capture Platform | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ admin_assets_url('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    @yield('page-css')

    <!-- Theme style -->

    <link rel="stylesheet" href="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.css') }}">
    <link rel="stylesheet" href="{{ admin_assets_url('dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ admin_assets_url('dist/css/skins/_all-skins.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('admin.template._navbar')

    @include('admin.template._sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('main-content')
    </div>
    <!-- /.content-wrapper -->

    @include('admin.template._footer')


    @include('admin.template._control_sidebar')


</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.0 -->
<script src="{{ admin_assets_url('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ admin_assets_url('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ admin_assets_url('plugins/fastclick/fastclick.js') }}"></script>


<!-- AdminLTE App -->
<script src="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.js') }}"></script>
<script src="{{ admin_assets_url('dist/js/app.min.js') }}"></script>
<script src="{{ admin_assets_url('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>

@yield('page-js')

<!-- AdminLTE for demo purposes -->
<script src="{{ admin_assets_url('dist/js/demo.js') }}"></script>
<script src="{{ base_url('public/js/custom.js') }}"></script>



@yield('end-script')

</body>
</html>
