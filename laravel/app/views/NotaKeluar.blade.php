<?php
/**
 * Created by PhpStorm.
 * User: Choirul
 * Date: 4/28/2015
 * Time: 8:18 AM
 */ ?>
<html>
<title>Excel</title>
<head>
    <style>
        @font-face{
            font-family: myFirstFont;
            src: url('{{asset('font/dotMatrix/DOTMATRI.ttf')}}');
        }
        .A4 {
            background-color: #FFFFFF;
            left: 50px;
            right: 5px;
            height: 14cm; /*Ukuran Panjang Kertas */
            width: 21cm; /*Ukuran Lebar Kertas */
            /*font-family: "Lucida Sans";*/
            font-family: verdana, helvetica, arial, sans-serif;
            /*font-family: Tahoma;*/


            /*font-family: "Times New Roman", serif;*/
        }

    </style>
</head>
<body class="A4">
{{$tabel}}
</body>
</html>