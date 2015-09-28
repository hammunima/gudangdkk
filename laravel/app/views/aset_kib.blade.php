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
        <li class="active">Kartu Inventaris Barang</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Kartu Inventaris Barang</strong></h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="{{route('aset-kib')}}">
                            <div class="form-group">
                                <label class="control-label col-md-2">Jenis Aset</label>

                                <div class="col-md-6">
                                    <select class="form-control select" name="aset">
                                        <option value="">Pilih Jenis Aset</option>
                                        <option value="tanah-tanah">Aset Tanah</option>
                                        <option value="mesin-peralatan dan mesin">Aset Peralatan dan Mesin</option>
                                        <option value="bangunan-gedung dan bangunan">Aset Gedung dan Bangunan</option>
                                        <option value="jalan-jalan irigasi dan jaringan lain">Aset Jalan Irigasi dan JAringan Lain</option>
                                        <option value="tetaplain-aset tetap lainnya">Aset Tetap Lainnya</option>
                                        <option value="lain-aset tidak berwujud">Aset Tidak Berwujud</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">&nbsp;</label>

                                <div class="col-md-1">
                                    <input type="submit" formtarget="_blank" class="btn btn-primary" value="Download">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END DEFAULT DATATABLE -->
    </div>
    <!-- END PAGE CONTENT WRAPPER -->
@stop
@section('ajax')
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function () {

        });
    </script>

@stop