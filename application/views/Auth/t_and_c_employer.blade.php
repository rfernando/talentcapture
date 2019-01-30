<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Talent Capture Platform</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ admin_assets_url('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ admin_assets_url('dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/iCheck/square/blue.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box" style="width: 720px; margin: 2% auto;">
    <div class="login-logo">
        <img alt="TalentCapture" src="{{ base_url('public/img/login_logo.png') }}">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        {{--<h3 class="login-box-msg">Terms and Conditions</h3>--}}

        <form action="" method="post">
            <div class="row">
                <div class="col-xs-12">
                    <?php 
                        $sitemsg = Site_messages::where('type','=','terms_and_condition_employer')->first();
                        echo $sitemsg->msg;
                    ?>
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-xs-6 pull-right">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" value="1" name="accept_terms">Accept and Continue</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="{{ admin_assets_url('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ admin_assets_url('bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>