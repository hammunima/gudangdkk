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
        <li><a href="#">Gudang</a></li>
        <li class="active">Penyesuaian</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data Penyesuaian</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <button id="tambah" class="btn btn-primary btn-rounded">
                                        Tambah Penyesuaian
                                    </button>
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
        <!-- MODALS -->
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="{{route('m-penyesuaian')}}">
                    <div class="panel panel-default @if(!$errors->has()) panel-toggled @endif" id="daftar">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Master Penyesuaian</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Nomor Penyesuaian</label>

                                        <div class="col-md-9 col-xs-12">
                                            <input type="text" name="nmr" class="form-control" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal</label>

                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                                <input type="text" class="form-control datepicker" name="tgl"
                                                       value="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label">&nbsp;</label>

                            <div class="row"></div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Keterangan</label>

                                <div class="col-md-7">
                                    <input type="text" name="ket" class="form-control"/>
                                </div>
                            </div>
                            <label class="control-label">&nbsp;</label>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Satuan</th>
                                    <th>Jumlah</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control select" data-live-search="true" name="addbrg">
                                            <option value=""></option>
                                            @foreach($m['brg'] as $row)
                                                <option value="{{$row->cKode.'-'.$row->id}}">{{$row->cKode.' - '.$row->cNama.' ('.$row->cSupplier.')'}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="sat" readonly class="form-control"></td>
                                    <td>
                                        <input type="number" id="jum" name="jum"
                                               onkeydown="validate_number(event);"
                                               class="form-control" style="text-align: right">
                                    </td>
                                    <td>
                                        <button type="button" id="add" class="btn btn-success">Tambah</button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody id="body-table">

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <a class="panel-collapse" href="#tabel">
                                <button class="btn btn-primary panel-collapse" href="#daftar" id="kembali">Kembali
                                </button>
                            </a>
                            <input type="submit" class="btn btn-primary pull-right" value="Simpan">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" id="repform" method="post" action="{{route('report-adj')}}">
                    <div class="panel panel-default panel-toggled " id="report">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Laporan Penyesuaian Barang</strong></h3>
                        </div>
                        <div class="panel-body">
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
                                        <button class="btn btn-primary panel-collapse" href="#report" id="kembali2">
                                            Kembali
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
        <!--END MODAL-->
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
@stop
@section('ajax')
    {{$tabel->script()}}
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#tambah").click(function () {
                $('input.form-control').val('');
                $('input[name^=tgl]').val('{{date('Y-m-d')}}');
                $('[name=nmr]').val('{{$m['baru']}}');
                $('#body-table').empty();
                $("#daftar").removeClass("panel-toggled");
                $("#tabel").addClass("panel-toggled");
            });
            $("#laporan").click(function () {
                $("#report").removeClass("panel-toggled");
                $("#tabel").addClass("panel-toggled");
            });
            $("#kembali").click(function () {
                $("#tabel").removeClass("panel-toggled");
            });
            $("#kembali2").click(function () {
                $("#tabel").removeClass("panel-toggled");
            });
            $('#add').click(function () {
                var b = $('[name=addbrg] option:selected').text();
                var brg = $('[name=addbrg]').val();
                var jum = $('[name=jum]').val();
                var sat = $('[name=sat]').val();
                $('#body-table').append('<tr>' +
                '<td><input type="text" class="hidden" name="kode[]" value="' + brg + '"><input type="text" class="form-control" readonly name="barang[]" value="' + b + '"></td>' +
                '<td><input type="text" class="form-control" readonly name="satuan[]" value="' + sat + '"></td>' +
                '<td><input type="text" class="form-control" readonly name="jumlah[]" value="' + jum + '"></td>' +
                '<td><a onclick="$(this).parent().parent().remove();" class="btn btn-icon-only red"><i class="fa fa-times"></i></a></td>' +
                '</tr>');
            });
            $('[name=addbrg]').change(function () {
                //alert('asdasd');
                var id = $(this).val();
                //$('input.form-control').val('');
                //$('#body-table').empty();
                $.ajax({
                    url: '{{route('get-stok-klr')}}',
                    type: 'POST',
                    data: {kode: id},
                    dataType: 'json',
                    success: function (result) {
                        $('[name=sat]').val(result.brg['cKdSatuan'] + '-' + result.brg['cSatuan']);
                    }
                })
            });
            /*$('#jum').keyup(function () {
             //alert('as');
             var tot = parseInt($('[name=jum]').val());
             var maks = parseInt(parseInt($(this).attr('max')));
             if (tot > maks) {
             alert('Stok tersisa hanya tinggal ' + maks);
             $('[name=jum]').val(maks);
             }

             });*/
        });

        function del(id) {
            $('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'delete/tbadj/' + id;
            }
            //$('[name=nama]').val(tes);
        }
        function validate_number(e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                        // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }
    </script>

@stop