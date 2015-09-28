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
        <li><a href="#">Data Master</a></li>
        <li class="active">Stok Barang</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data Stok Barang</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label class="control-label">&nbsp;</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button id="laporan" type="button" class="btn btn-primary ">Laporan</button>
                                </div>
                            </div>
                        </div>
                        <label class="control-label">&nbsp;</label>
                        {{$tabel->render()}}
                    </div>
                </div>
            </div>
        </div>
        <!-- END DEFAULT DATATABLE -->
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" id="repform" method="post" action="{{route('lap-stok')}}">
                    <div class="panel panel-default panel-toggled " id="daftar">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Laporan Stok Barang</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Format Laporan</label>

                                <div class="col-md-5">
                                    <select class="form-control select" name="format">
                                        <option value="0">PDF</option>
                                        <option value="1">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Jenis Laporan</label>

                                <div class="col-md-5">
                                    <select class="form-control select" name="jns">
                                        <option value="0">Semua</option>
                                        <option value="1">per Jenis Barang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group jns hidden" id="p_jb">
                                <label class="col-md-2 control-label">Jenis Barang</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_jb">
                                        <option value="">Semua Jenis</option>
                                        @foreach($m['jb'] as $row)
                                            <option value="{{$row->jenis}}">{{$row->jenis}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Periode</label>

                                <div class="col-md-2">
                                    <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                        <input type="text" class="form-control datepicker" name="awal"
                                               placeholder="{{date('Y-m-d')}}" value="">
                                    </div>
                                </div>
                                <label class="col-md-1 control-label">Sampai</label>

                                <div class="col-md-2">
                                    <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                        <input type="text" class="form-control datepicker" name="akhir"
                                               placeholder="{{date('Y-m-d')}}" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">&nbsp;</label>

                                <div class="col-md-5">
                                    <a class="panel-collapse" href="#tabel">
                                        <button class="btn btn-primary panel-collapse" href="#daftar" id="kembali">Kembali
                                        </button>
                                    </a>
                                    <input type="submit" formtarget="_blank" class="btn btn-primary" value="Download">

                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">


                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
@stop
@section('ajax')
    {{$tabel->script()}}
    <script type="text/javascript" src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-datepicker.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#laporan").click(function () {
                $("#daftar").removeClass("panel-toggled");
                $("#tabel").addClass("panel-toggled");
            });
            $("#kembali").click(function (e) {
                $("#tabel").removeClass("panel-toggled");
            });
            $('[name=jns]').change(function () {
                if ($(this).val() == '1') {
                    $('.jns').addClass('hidden');
                    $('#p_jb').removeClass('hidden');
                } else {
                    $('.jns').addClass('hidden');
                }
            });
        });

    </script>

@stop