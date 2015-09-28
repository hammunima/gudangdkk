<!DOCTYPE html>
<html lang="en">
<head>
    <!-- META SECTION -->
    <title>Gudang DKK </title>
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
<!-- START PAGE CONTAINER -->
<div class="page-container">

    <!-- START PAGE SIDEBAR -->
    <div class="page-sidebar">
        <!-- START X-NAVIGATION -->
        <ul class="x-navigation">
            <li class="">
                <label style="font-size: 14pt;color: white;padding-top: 5%">
                    <marquee direction="left" scrollamount="2" align="center">Aplikasi Gudang Dinas Kesehatan</marquee>
                </label>
            </li>
            <li class="xn-profile">
                <a href="#" class="profile-mini">
                    <img src="{{asset('img/gudang-dkk.jpg')}}" alt="John Doe"/>
                </a>

                <div class="profile">
                    <div class="profile-image">
                        <img src="{{asset('img/gudang-dkk.jpg')}}" height="100" alt="John Doe"/>
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name">Dinas Kesehatan</div>
                        <div class="profile-data-title">Gudang</div>
                    </div>
                </div>
            </li>
            <li>
                <a href="{{route('dash')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Dashboard</span></a>
            </li>
            <li id="dkk" class="xn-title"><strong>Master</strong></li>
            <li id="dkk" class="xn-openable">
                <a href="#"><span class="fa fa-files-o"></span> <span class="xn-text">Data Master</span></a>
                <ul>
                    <li><a href="{{route('m-barang')}}"><span class="fa fa-image"></span>Barang</a></li>
                    <li><a href="{{route('m-supplier')}}"><span class="fa fa-image"></span>Supplier</a></li>
                    <li><a href="{{route('m-sumberanggaran')}}"><span class="fa fa-image"></span>Sumber Anggaran</a>
                    </li>
                    <li><a href="{{route('m-bidang')}}"><span class="fa fa-image"></span>Bidang</a></li>
                    <li><a href="{{route('m-puskesmas')}}"><span class="fa fa-image"></span>Puskesmas</a></li>
                </ul>
            </li>
            <li id="dkk" class="xn-openable">
                <a href="#"><span class="fa fa-files-o"></span> <span class="xn-text">Support Master</span></a>
                <ul>
                    <li><a href="{{route('m-jenis')}}"><span class="fa fa-image"></span>Jenis</a></li>
                    <li><a href="{{route('m-tipe')}}"><span class="fa fa-image"></span>Tipe</a></li>
                    <li><a href="{{route('m-merk')}}"><span class="fa fa-image"></span>Merk</a></li>
                    <li><a href="{{route('m-satuan')}}"><span class="fa fa-image"></span>Satuan</a></li>
                </ul>
            </li>
            <li id="dkk" class="xn-title"><strong>Gudang</strong></li>
            <li id="dkk">
                <a href="{{route('m-penerimaan')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Penerimaan Barang</span></a>
            </li>
            <li id="dkk">
                <a href="{{route('m-pengeluaran')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Pengeluaran Barang</span></a>
            </li>
            <li id="dkk">
                <a href="{{route('m-penyesuaian')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Penyesuaian Barang</span></a>
            </li>
            <li id="dkk">
                <a href="{{route('m-stok')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Lihat Stock Barang</span></a>
            </li>
            <!-- Aplikasi Gudang Puskesmas-->
            <li id="dis" class="xn-title"><strong>Gudang</strong></li>
            <li id="dis">
                <a href="{{route('m-stok')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Stok Barang DKK</span></a>
            </li>
            <li id="abc" class="xn-title"><strong>Puskesmas - Master</strong></li>
            <li id="abc">
                <a href="{{route('pkm-supplier')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Supplier</span></a>
            </li>
            <li id="abc">
                <a href="{{route('pkm-user')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">User Login</span></a>
            </li>
            <li id="asd" class="xn-title"><strong>Puskesmas</strong></li>
            <li id="asd">
                <a href="{{route('pkm-masuk')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Penerimaan Barang</span></a>

                <div class="informer informer-warning" id="count2"></div>
            </li>
            <li id="abc">
                <a href="{{route('pkm-alokasi')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Pengeluaran Barang</span></a>
            </li>
            <li id="abc">
                <a href="{{route('m-stok-pkm')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Stok Barang Puskesmas</span></a>
            </li>
            <li id="gudang" class="xn-title"><strong>Admin DKK</strong></li>
            <li id="gudang">
                <a href="{{route('dkk-masuk')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Penerimaan</span></a>

                <div class="informer informer-danger" id="count"></div>
            </li>
            <li id="gudang">
                <a href="{{route('dkk-alokasi')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Pengeluaran</span></a>

            </li>
            <li id="gudang">
                <a href="{{route('dkk-stok-pkm')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Stok Barang</span></a>
            </li>
            <li id="gudang" class="xn-title"><strong>Master</strong></li>
            <li id="gudang">
                <a href="{{route('dkk-unit')}}"><span class="fa fa-desktop"></span> <span
                            class="xn-text">Unit</span></a>
            </li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
        </ul>
        <!-- END X-NAVIGATION -->

    </div>
    <!-- END PAGE SIDEBAR -->

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <!-- START X-NAVIGATION VERTICAL -->
        <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
            <!-- TOGGLE NAVIGATION -->
            <li class="xn-icon-button">
                <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
            </li>
            <li class="xn-icon-button pull-right">
                <a href="{{route('logout')}}" title='Log Out' class="mb-control" data-box="#mb-signout"><span
                            class="fa fa-power-off"></span></a>
            </li>
            <!-- END TOGGLE NAVIGATION -->
            <li class="pull-right">
                <label style="font-size: 14pt;color: white;padding-top: 15%"><img style="padding-bottom: 5%"
                                                                                  src="{{asset('img/logo-ic-white.png')}}">&nbsp;2015&nbsp;
                </label>&nbsp;&nbsp;&nbsp;
            </li>
            <!-- SIGN OUT
            <li class="xn-icon-button pull-right">
                <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>
            </li>
             END SIGN OUT -->
        </ul>
        <!-- END X-NAVIGATION VERTICAL -->
        @yield('content')
    </div>
    <!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->

<!-- MESSAGE BOX-->
<div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
    <div class="mb-container">
        <div class="mb-middle">
            <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
            <div class="mb-content">
                <p>Are you sure you want to log out?</p>

                <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
            </div>
            <div class="mb-footer">
                <div class="pull-right">
                    <a href="{{route('logout')}}" class="btn btn-success btn-lg">Yes</a>
                    <button class="btn btn-default btn-lg mb-control-close">No</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MESSAGE BOX-->

<!-- START Notify -->

<!-- START PRELOADS -->
<audio id="audio-alert" src="{{asset('audio/alert.mp3')}}" preload="auto"></audio>
<audio id="audio-fail" src="{{asset('audio/fail.mp3')}}" preload="auto"></audio>
<!-- END PRELOADS -->
<!-- START PLUGINS -->
<script type="text/javascript" src="{{asset('js/plugins/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/plugins/jquery/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap.min.js')}}"></script>
<!-- END PLUGINS -->
<!-- START SCRIPTS -->

<!-- THIS PAGE PLUGINS -->
<script type='text/javascript' src='{{asset('js/plugins/icheck/icheck.min.js')}}'></script>
<script type="text/javascript" src="{{asset('js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js')}}"></script>
@yield('ajax')
<!-- END THIS PAGE PLUGINS -->

<!-- START TEMPLATE -->
<script type="text/javascript" src="{{asset('js/plugins.js')}}"></script>
<script type="text/javascript" src="{{asset('js/actions.js')}}"></script>
<!-- END TEMPLATE -->
<!-- END SCRIPTS -->
<script type='text/javascript' src='{{asset('js/plugins/noty/jquery.noty.js')}}'></script>
<script type='text/javascript' src="{{asset('js/plugins/noty/layouts/topRight.js')}}"></script>
<script type='text/javascript' src="{{asset('js/plugins/noty/themes/default.js')}}"></script>
@if(Session::has('register_success'))
    <script type="text/javascript">
        noty({text: '{{Session::get('register_success')}}', layout: 'topRight', type: 'success'});
    </script>
@endif

<script>
    $(document).ready(function () {
        var div = '{{Auth::user()->role}}';
        if (div == '0') {
            setTimeout(function () {
                cekNew();
            }, 3000);
        }
        if (div == '1') {
            setTimeout(function () {
                cekNew2();
            }, 3000);
        }

    });
    function cekNew() {
        $.ajax({
            url: '{{route('dkk-cek')}}',
            type: 'POST',
            dataType: 'json',
            success: function (result) {
                if (result.cek > 0) {
                    $('#count').html(result.cek);
                }
            }
        })
        setTimeout(function () {
            cekNew();
        }, 3000);
    }
    function cekNew2() {
        $.ajax({
            url: '{{route('pkm-cek')}}',
            type: 'POST',
            dataType: 'json',
            success: function (result) {
                if (result.cek > 0) {
                    $('#count2').html(result.cek);
                }
            }
        })
        setTimeout(function () {
            cekNew2();
        }, 3000);
    }

    $(function () {
        var url = window.location;
        // Will only work if string in href matches with location
        $('ul.x-navigation a[href="' + url + '"]').parent().addClass('active');
        $('li.xn-openable ul a[href="' + url + '"]').parent().parent().parent().addClass('active');
        $('li.xn-openable ul a[href="' + url + '"]').parent().parent().parent().parent().parent().addClass('active');
        var div = '{{Auth::user()->role}}';
        if (div == '9') {
            $('li[id^=abc]').addClass('hidden');
            $('li[id^=asd]').addClass('hidden');
            $('li[id^=dis]').addClass('hidden');
            $('li[id^=gudang]').addClass('hidden');
        } else if (div == '0') {
            $('li[id^=abc]').addClass('hidden');
            $('li[id^=asd]').addClass('hidden');
            $('li[id^=dis]').addClass('hidden');
            $('button[id^=tambah]').addClass('hidden');
            $('li[id^=dkk]').addClass('hidden');
            $('.profile-data-name').empty();
            $('.profile-data-name').html('Admin');
            $('.profile-data-title').empty();
            $('.profile-data-title').html('DKK');
        } else if (div == '2') {
            $('li[id^=dkk]').addClass('hidden');
            $('li[id^=gudang]').addClass('hidden');
            $('li[id^=abc]').addClass('hidden');
            $('.profile-data-name').empty();
            $('.profile-data-name').html('Puskesmas');
            $('.profile-data-title').empty();
            $('.profile-data-title').html('{{Auth::user()->nama_puskesmas}}');
        } else {
            $('li[id^=dkk]').addClass('hidden');
            $('li[id^=gudang]').addClass('hidden');
            $('.profile-data-name').empty();
            $('.profile-data-name').html('Puskesmas');
            $('.profile-data-title').empty();
            $('.profile-data-title').html('{{Auth::user()->nama_puskesmas}}');
        }
    });
</script>
</body>
</html>






