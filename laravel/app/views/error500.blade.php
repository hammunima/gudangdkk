<?php
/**
 * Created by PhpStorm.
 * User: Choirul
 * Date: 5/6/2015
 * Time: 9:44 AM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META SECTION -->
    <title>Error</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <link rel="icon" href="favicon.ico" type="image/x-icon"/>
    <!-- END META SECTION -->

    <!-- LESSCSS INCLUDE -->
    <link rel="stylesheet/less" type="text/css" href="{{asset('css/styles.less')}}"/>
    <script type="text/javascript" src="{{asset('js/plugins/lesscss/less.min.js')}}"></script>
    <!-- EOF LESSCSS INCLUDE -->
</head>
<body>
<div class="error-container">
    <div class="error-code">500</div>
    <div class="error-text">Internal server error</div>
    <div class="error-subtext">The server encountered an internal error or misconfiguration and was unable to complete
        your request.
    </div>
    <div class="error-actions">
        <div class="col-md-12">
            <button class="btn btn-primary btn-block btn-lg" onClick="history.back();">Kembali</button>
        </div>
    </div>
</div>
</body>
</html>






