<?php
/**
 * Created by PhpStorm.
 * User: Choirul
 * Date: 3/25/2015
 * Time: 9:06 AM
 */
?>
@extends('layout.main_layout')
@section('content')

    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li class="active">Dashboard</li>
    </ul>
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Selamat Datang
            @if(Auth::user()->role=='9')
                Gudang Dinas Kesehatan
            @elseif(Auth::user()->role=='0')
                {{'Admin DKK'}}
            @else
                {{'Puskesmas '.Auth::user()->nama_puskesmas}}
            @endif
        </h2>
        <br>
        <img src="{{asset('img/gudang-dkk.jpg')}}" width="1020">
    </div>

    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
@stop
@section('ajax')

@stop