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
                        <h3 class="panel-title"><strong>Kartu Inventaris Ruangan</strong></h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="{{route('aset-kir')}}">
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Unit/Ruangan</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="unit">
                                        <option value="-">-Pilih Unit-</option>
                                        @if(Auth::user()->id_puskesmas <>'999')
                                            @foreach($m['unit'] as $row)
                                                <option value="{{$row->id.'-'.$row->nama_unit}}">{{$row->id.'-'.$row->nama_unit}}</option>
                                            @endforeach
                                        @endif
                                        @if(Auth::user()->id_puskesmas =='999')
                                            @foreach($m['bidang'] as $row)
                                                <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cKode.'-'.$row->cNama}}</option>
                                            @endforeach
                                        @endif
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