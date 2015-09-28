<?php
/**
 * Created by PhpStorm.
 * User: Choirul
 * Date: 4/1/2015
 * Time: 10:55 AM
 */
?>
<!DOCTYPE html>
<html lang="en">
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
<div class="error-container">
    <div class="error-code">404</div>
    <div class="error-text">page not found</div>
    <div class="error-subtext">Unfortunately we're having trouble loading the page you are looking for. Please wait a
        moment and try again or use action below.
    </div>
    <div class="error-actions">
        <div class="row">
            <div class="col-md-6">
                <a href="{{route('dash')}}">
                    <button class="btn btn-info btn-block btn-lg">Back to dashboard</button>
                </a>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary btn-block btn-lg" onClick="history.back();">Previous page</button>
            </div>
        </div>
    </div>
    <div class="error-subtext">Or you can use search to find anything you need.</div>
    <div class="row">
        <div class="col-md-12">
            <div class="input-group">
                <input type="text" placeholder="Search..." class="form-control"/>

                <div class="input-group-btn">
                    <button class="btn btn-primary"><span class="fa fa-search"></span></button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>






