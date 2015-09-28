<!DOCTYPE html>
<html lang="en" class="body-full-height">
<head>
    <!-- META SECTION -->
    <title>Atlant - Responsive Bootstrap Admin Template</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon"/>
    <!-- END META SECTION -->

    <!-- LESSCSS INCLUDE -->
    <link rel="stylesheet/less" type="text/css" href="{{asset('css/styles.less')}}"/>
    <script type="text/javascript" src="{{asset('js/plugins/lesscss/less.min.js')}}"></script>
    <!-- EOF LESSCSS INCLUDE -->
</head>
<body>

<div class="login-container">

    <div class="login-box animated fadeInDown">
        <div class="login-logo"></div>
        <div class="login-body">
            <div class="login-title"><strong>Welcome</strong>, Please login</div>
            @if(Session::has('message'))
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <strong>{{Session::get('message')}}</strong>
                </div>
            @endif
            <form action="{{route('login')}}" class="form-horizontal" method="post">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="username" class="form-control" placeholder="Username"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        &nbsp;
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-info btn-block">Log In</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="login-footer">
            <div class="pull-left">
                <img src="{{asset('img/logo-ic-white.png')}}" width="8"> 2015 Gudang DKK
            </div>
            <div class="pull-right">
                IT Dinas Kesehatan
            </div>
        </div>
    </div>

</div>

<script type='text/javascript' src='{{asset('js/plugins/noty/jquery.noty.js')}}'></script>

<script type='text/javascript' src='{{asset('js/plugins/noty/themes/default.js')}}'></script>

</body>
</html>






