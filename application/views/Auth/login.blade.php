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

    <link rel="stylesheet" href="{{ admin_assets_url('custom.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
    <div id="loader" style="display: none">
    <img alt="TalentCapture" src="{{ base_url('public/img/tc_logo_loader.png') }}">
    </div>
    <div class="loader" style="display: none;"><img src="{{ base_url('public/img/loader.gif') }}" height="60"></div>
<div class="login-box">
    <div class="login-logo">
        <img alt="TalentCapture" src="{{ base_url('public/img/login_logo.png') }}">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form id="login_form">
            {{ flash_msg(); }}
            {{ generate_form_fields($loginFields) }}
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="button" class="btn btn-primary btn-block btn-flat" onclick="submitForm();">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="{{ base_url('Auth/oauth_authenticate') }}" class="btn btn-block btn-social btn-linkedin btn-flat"><i class="fa fa-linkedin"></i> Sign in using
                LinkedIn</a>
            {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
                Google+</a>--}}
        </div>
        <!-- /.socsial-auth-links -->

        <a href="{{ base_url('forgot_password') }}">I forgot my password</a><br>
        <a href="{{ base_url('register') }}" class="text-center">Register a new membership</a>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="{{ admin_assets_url('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ admin_assets_url('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ admin_assets_url('plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(function () {

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

    });

    function submitForm() {
        $.ajax({
            type:'post',
            url:'{{ base_url('Auth/authenticate') }}',
            data:$("#login_form").serialize(),
            beforeSend: function() {    
                $('.login-box').css('display','none');
                $('#loader').css('display','block');
                $('.loader').css('display','block');
            },
            success:function(result) {
                if (result == 1) {
                    window.location.reload();
                }else if (result == 2) {
                     window.location.reload();
                 }else if (result == 3) {
                    window.location.reload();
                 }else{
                    window.location = result;
                }
            }
        })
    }

</script>
</body>
</html>
