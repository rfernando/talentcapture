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
    @yield('page-css')
    <link rel="stylesheet" href="{{ admin_assets_url('dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ admin_assets_url('dist/css/skins/_all-skins.min.css') }}">

    <link rel="stylesheet" href="{{ admin_assets_url('custom.css') }}">
    
    <link rel="stylesheet" href="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.css') }}">

    <!-- jQuery 2.2.0 -->
    <script src="{{ admin_assets_url('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>

    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="{{ base_url('dashboard') }}" class="navbar-brand"><img class="navbar-brand" alt="TalentCapture" src="{{ base_url('public/img/dashboard_logo.png') }}"></a>

                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li {{ check_current_page('dashboard',1) }}><a href="{{ base_url('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard <span class="sr-only">(current)</span></a></li>
                        <li class="dropdown {{ check_current_page('jobs',1) ? 'active' : ''}}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Jobs <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li class="job-list" data-job-status="#active"><a href="{{ base_url('jobs#active')  }}">Active Jobs </a></li>
                                <li class="job-list" data-job-status="#closed"><a href="{{ base_url('jobs#closed')  }}">Closed Jobs </a></li>
                                <li class="divider"></li>
                                <li><a href="{{ base_url('jobs/add_new') }}">Add New Job</a></li>
                            </ul>
                        </li>
                        <!-- Add SO's Drop Down-->
                        @if(get_user('type') == 'agency')
                            <li class="dropdown" {{ check_current_page('searches',1)}} >
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">My TalentGrams <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="so-list" data-so-status="#active"><a href="{{ base_url('searches#active')  }}">Active TalentGrams </a></li>
                                    <li class="so-list" data-so-status="#closed"><a href="{{ base_url('searches#closed')  }}">Archived TalentGrams </a></li>
                                </ul>
                            </li>
                        @endif
                        <li {{ check_current_page('agency',1) }}><a href="{{ base_url('agency') }}">My Agency Recruiters</a></li>
                        <!-- <li {{ check_current_page('blog',1) }}><a href="{{ base_url('blogs') }}">Blogs & News</a></li>-->
                    </ul>
                    {{--<form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search">
                        </div>
                    </form>--}}
                </div>
                <!-- /.navbar-collapse -->
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->

                        @include('template._new_messages')
                        <!-- /.messages-menu -->

                        <!-- Notifications Menu -->
                        <li class="dropdown notifications-menu">
                            <?php $notificationcount =  User_notifications::where('user_id','=',get_user('id'))->where('cn_status','=',0)->count(); ?>
                            <?php $notifications =  User_notifications::where('user_id','=',get_user('id'))->where('status','=',0); ?>
                            <!-- Menu toggle button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                @if($notificationcount>0)
                                    <span class="label label-warning">{{$notificationcount}}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu">

                                <li class="header">You have {{count($notifications)}} notifications</li>
                                <li>
                                    <!-- Inner Menu: contains the notifications -->
                                    <ul class="menu">

                                        @foreach($notifications->orderBy('created_at', 'desc')->get() as $notification)
                                            <li><!-- start notification -->
                                                <a href="{{ base_url('dashboard/update_notification_status/'.$notification->id) }}">
                                                    <i class="fa fa-circle text-aqua"></i> {{$notification -> notification_text}}
                                                </a>
                                            </li>
                                        @endforeach
                                        <!-- end notification -->
                                    </ul>
                                </li>
                                <!-- <li class="footer"><a href="#">View all</a></li> -->
                            </ul>
                        </li>
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                @if(get_user('profile_pic') != '')
                                    <img class="user-image" alt="User Image" src="{{ base_url('public/uploads/'.get_user('profile_pic')) }}">
                                @else
                                    <img class="user-image" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                                @endif
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{{ get_user('first_name').' '.get_user('last_name') }}</span>
                            </a>
                            <ul class="dropdown-menu" style="width:100px">
                                <!-- The user image in the menu -->
                                <!-- <li class="user-header">
                                    @if(get_user('profile_pic'))
                                        <img class="img-circle" alt="User Image" src="{{ base_url('public/uploads/'.get_user('profile_pic')) }}">
                                    @else
                                        <img class="img-circle" alt="User Image" src="{{ base_url('public/img/default_profile_pic.jpg') }}">
                                    @endif
                                    <p>
                                        {{ get_user('first_name').' '.get_user('last_name') }}
                                        <small>{{ ucfirst(get_user('type')) }}</small>
                                    </p>
                                </li>-->
                                <!-- Menu Body -->
                                <!--<li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                        </div>
                                    </div>
                                </li>  -->
                                <!-- Menu Footer-->
                                <!--<li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ base_url('profile/update') }}" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ base_url('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>-->
                                <li>
                                    <a href="{{ base_url('profile/update') }}">Settings</a>
                                </li>
                                <li>
                                    <a href="{{ base_url('logout') }}">Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-custom-menu -->
            </div>
            <!-- /.container-fluid -->
        </nav>
    </header>
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container" id="container">
            @yield('main-content')
        </div>
        <!-- /.container -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <!--<div class="container">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.3.3
            </div>
            <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
            reserved.
        </div> -->
        <!-- /.container  -->
    </footer>
</div>
<!-- ./wrapper -->



<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ admin_assets_url('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- SlimScroll 
<script src="{{ admin_assets_url('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>-->
<!-- FastClick -->
<script src="{{ admin_assets_url('plugins/fastclick/fastclick.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

@yield('page-js')

<!-- AdminLTE App -->
<script src="{{ admin_assets_url('dist/js/app.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ admin_assets_url('dist/js/demo.js') }}"></script>


<script src="{{ admin_assets_url('plugins/jQuery-validation/jquery.validate.min.js') }}"></script>

<script src="{{ admin_assets_url('plugins/bootstrap-star-rating/star-rating.min.js') }}"></script>

<script src="{{ admin_assets_url('plugins/number-formatter/hashtable.js') }}"></script>

<script src="{{ admin_assets_url('plugins/number-formatter/jquery.numberformatter-1.2.4.jsmin.js') }}"></script>

<script type="application/javascript">
    var baseURL = '{{ base_url() }}';
</script>

<script src="{{ base_url('public/js/custom.js') }}"></script>



@yield('end-script')
</body>
</html>
