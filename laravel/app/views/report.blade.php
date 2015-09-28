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
        <li class="active">Laporan</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#tab-penerimaan" role="tab" data-toggle="tab">Penerimaan</a></li>
                        <li><a href="#tab-pengeluaran" role="tab" data-toggle="tab">Pengeluaran</a></li>
                    </ul>
                    <div class="panel-body tab-content">
                        <div class="tab-pane active" id="tab-penerimaan">
                            <form class="form-horizontal" method="post" action="{{route('penerimaan-aset')}}">
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Jenis Penerimaan</label>

                                    <div class="col-md-6 col-xs-12">
                                        <select class="form-control select" id="p_jenis" name="jenis">
                                            <option value="1">Seluruh Penerimaan</option>
                                            <option value="2">Pengadaan</option>
                                            <option value="3">Transfer Masuk</option>
                                            <option value="4">Inventarisasi</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Periode</label>

                                    <div class="col-md-2 col-xs-4">
                                        <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            <input type="text" class="form-control datepicker" name="awal"
                                                   placeholder="{{date('Y-m-d')}}" value="">
                                        </div>
                                    </div>
                                    <label class="col-md-2 col-xs-4 control-label"
                                           style="text-align: center">Sampai</label>

                                    <div class="col-md-2 col-xs-4">
                                        <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            <input type="text" class="form-control datepicker" name="akhir"
                                                   placeholder="{{date('Y-m-d')}}" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Format</label>

                                    <div class="col-md-6 col-xs-12">
                                        <select class="form-control select" id="p_format" name="format">
                                            <option value="1">PDF</option>
                                            <option value="2">Excel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">&nbsp;</label>

                                    <div class="col-md-2 col-xs-12">
                                        <input type="submit" id="p_submit" formtarget="_blank" class="btn btn-primary"
                                               value="Submit">
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane" id="tab-pengeluaran">
                            <form class="form-horizontal" method="post" action="{{route('pengeluaran-aset')}}">
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Periode</label>

                                    <div class="col-md-2 col-xs-4">
                                        <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            <input type="text" class="form-control datepicker" name="awal"
                                                   placeholder="{{date('Y-m-d')}}" value="">
                                        </div>
                                    </div>
                                    <label class="col-md-2 col-xs-4 control-label"
                                           style="text-align: center">Sampai</label>

                                    <div class="col-md-2 col-xs-4">
                                        <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            <input type="text" class="form-control datepicker" name="akhir"
                                                   placeholder="{{date('Y-m-d')}}" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Format</label>

                                    <div class="col-md-6 col-xs-12">
                                        <select class="form-control select" id="p_format" name="format">
                                            <option value="1">PDF</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">&nbsp;</label>

                                    <div class="col-md-2 col-xs-12">
                                        <input type="submit" id="p_submit" formtarget="_blank" class="btn btn-primary"
                                               value="Submit">
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
@section('ajax')
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#p_submit").click(function () {
                var a = $('#p_jenis').val();
                var b = $('#p_format').val();
                if (a == 1 && b == 1) {
                    alert('format tidak tersedia');
                    return false;
                }
            });
        });
    </script>

@stop