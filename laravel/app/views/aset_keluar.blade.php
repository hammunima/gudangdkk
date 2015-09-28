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
        <li class="active">Pengeluaran</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data Pengeluaran Barang</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <button id="tambah" class="btn btn-primary btn-rounded">
                                        Tambah Pengeluaran
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button id="laporan" type="button" class="btn btn-primary hidden">Laporan</button>
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
                <form class="form-horizontal" method="post" action="{{route('aset-keluar')}}">
                    <div class="panel panel-default @if(!$errors->has()) panel-toggled @endif" id="daftar">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Master Alokasi</strong></h3>
                        </div>
                        <div class="panel-body" id="bodi">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Nomor Alokasi</label>

                                        <div class="col-md-9 col-xs-12">
                                            <input type="text" name="nmr" class="form-control" readonly>
                                            <input type="text" name="edit" class="hidden" value="0">
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
                                <label class="col-md-2 col-xs-12 control-label">Tujuan</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" id="tujuan"
                                            name="tujuan">
                                        <option value="">--Pilih Tujuan--</option>
                                        <option value="intern">Unit Intern</option>
                                        <option value="ekstern">Unit Ekstern</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group hidden" id="int">
                                <label class="col-md-2 col-xs-12 control-label">Unit Intern</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" id="unit" name="unit">
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
                            <div class="form-group hidden" id="ext">
                                <label class="col-md-2 col-xs-12 control-label">Unit Ekstern</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="extern">
                                        <option value="-">-Pilih Unit-</option>
                                        @foreach($m['int'] as $row)
                                            <option value="{{$row->id.'-'.$row->nama_puskesmas}}">{{$row->nama_puskesmas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-7 col-xs-12">
                                    <input type="text" name="ket" class="form-control"/>
                                </div>
                            </div>
                            <label class="control-label"></label>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Jenis</th>
                                    <th>Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td style="width: 35%;">
                                        <select class="form-control select" data-live-search="true" name="addbrg1">
                                            <option value=""></option>
                                            @foreach($m['aset'] as $row)
                                                <option value="{{$row->id.'-'.$row->nama}}">{{$row->id_aset.' - '.$row->nama.' - '.$row->nama_ruangan}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="jenis1" readonly class="form-control"></td>
                                    <td><input type="text" name="sat1" readonly class="form-control"></td>
                                    <td>
                                        <input type="number" max="0" id="jum" name="jum1"
                                               onkeydown="validate_number(event);"
                                               class="form-control" style="text-align: right">
                                    </td>
                                    <td>
                                        <input type="text" name="ktrg1" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" id="add1" class="btn btn-success">Tambah</button>
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

        <!--END MODAL-->
        <!-- END PAGE CONTENT WRAPPER -->
        <!-- MODALS -->
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" id="repform" method="post" action="{{route('lap-keluar-pkm')}}">
                    <div class="panel panel-default panel-toggled " id="report">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Laporan Alokasi Barang</strong></h3>
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
                                    <select class="form-control select" id="jns" name="jns">
                                        <option value="0">Semua</option>
                                        <option value="id_unit">Per Unit</option>
                                        <option value="id_barang">Per Barang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group jns hidden" id="p_unit">
                                <label class="col-md-2 control-label">Unit</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_unit">
                                        <option value="">Semua Unit</option>
                                        @foreach($m['unit'] as $row)
                                            <option value="{{$row->id}}">{{$row->nama_unit}}</option>
                                        @endforeach
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
                            <div class="form-group jns hidden" id="p_b">
                                <label class="col-md-2 control-label">Barang</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_b">
                                        <option value="">Semua Barang</option>
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
        <div class="modal" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="defModalHead"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                    aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="defModalHead">Detail Pengeluaran</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                            </thead>
                            <tbody id="detail">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
                    </div>

                </div>
            </div>
        </div>
        <!--END MODAL-->
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
                $('select.form-control').val('0000-');
                $('.select').selectpicker('refresh');
                $('[name=edit]').val('0');
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
            $('[name=jns]').change(function () {
                if ($(this).val() == 'id_unit') {
                    $('.jns').addClass('hidden');
                    $('#p_unit').removeClass('hidden');
                } else if ($(this).val() == 'id_barang') {
                    $('.jns').addClass('hidden');
                    $('#p_jb').removeClass('hidden');
                    $('#p_b').removeClass('hidden');
                } else {
                    $('.jns').addClass('hidden');
                }
            });
            $('#tujuan').change(function () {
                if ($(this).val() == 'intern') {
                    $('#ext').addClass('hidden');
                    $('#int').removeClass('hidden');
                } else if ($(this).val() == 'ekstern') {
                    $('#int').addClass('hidden');
                    $('#ext').removeClass('hidden');
                } else {
                    $('#ext').addClass('hidden');
                    $('#int').addClass('hidden');
                }
            });
            $('[name=p_jb]').change(function () {
                var id = $(this).val();
                $('[name=p_b]').empty();
                $.ajax({
                    url: '{{route('get-b')}}',
                    type: 'POST',
                    data: {kode: id},
                    dataType: 'json',
                    success: function (result) {
                        $('[name=p_b]').append('<option value="">Semua Barang</option>');
                        for (i = 0; i < result.data.length; i++) {
                            $('[name=p_b]').append('' +
                            '<option value="' + result.data[i]['id_barang'] + '">' +
                            result.data[i]['id_barang'] + '-' + result.data[i]['nama_barang'] + '</option>');
                        }
                        $('.select').selectpicker('refresh');
                    }
                })
            });
            $('#add').click(function () {
                //var brg = $("[name=addbrg] option:selected").text();
                var brg = $('[name=addbrg]').val();
                var brg2 = $('[name=addbrg] option:selected').text();
                var jum = $('[name=jum]').val();
                var sat = $('[name=sat]').val();
                var tot = $('[name=ktrg]').val();
                if (brg == '') {
                    alert('Masukkan barang dahulu');
                    $('[name=addbrg]').focus();
                } else if (jum == '' || jum == 0) {
                    alert('Jumlah tidak boleh kosong');
                    $('[name=jum]').focus();
                } else {
                    $('#body-table').append('<tr class="success" style="font-size: 10pt">' +
                    '<td><input type="text" class="hidden" name="tipe[]" value="hp"><label class="control-label">Habis Pakai</label></td>' +
                    '<td><input type="text" class="hidden" name="barang[]" value="' + brg + '"><label class="control-label">' + brg2 + '</label></td>' +
                    '<td><input type="text" class="hidden" name="satuan[]" value="' + sat + '"><label class="control-label">' + sat + '</label></td>' +
                    '<td><input type="text" class="hidden" name="jumlah[]" value="' + jum + '"><label class="control-label pull-right">' + jum + '</label></td>' +
                    '<td><input type="text" class="hidden" name="keterangan[]" value="' + tot + '"><label class="control-label">' + tot + '</label></td>' +
                    '<td><a onclick="$(this).parent().parent().remove();" class="btn btn-icon-only red"><i class="fa fa-times"></i></a></td>' +
                    '</tr>');
                    $('[name=jum]').val('');
                    $('[name=sat]').val('');
                    $('[name=ktrg]').val('');
                    $('[name=addbrg]').val('');
                    $('.select').selectpicker('refresh');
                }
            });
            $('#add1').click(function () {
                //var brg = $("[name=addbrg] option:selected").text();
                var brg = $('[name=addbrg1]').val();
                var brg2 = $('[name=addbrg1] option:selected').text();
                var jum = $('[name=jum1]').val();
                var j = $('[name=jenis1]').val();
                var sat = $('[name=sat1]').val();
                var tot = $('[name=ktrg1]').val();
                if (brg == '') {
                    alert('Masukkan barang dahulu');
                    $('[name=addbrg1]').focus();
                } else if (jum == '' || jum == 0) {
                    alert('Jumlah tidak boleh kosong');
                    $('[name=jum1]').focus();
                } else {
                    $('#body-table').append('<tr class="success" style="font-size: 10pt">' +
                    '<td><input type="text" class="hidden" name="barang[]" value="' + brg + '"><label class="control-label">' + brg2 + '</label></td>' +
                    '<td><input type="text" class="hidden" name="jenis[]" value="' + j + '"><label class="control-label">' + j + '</label></td>' +
                    '<td><input type="text" class="hidden" name="satuan[]" value="' + sat + '"><label class="control-label">' + sat + '</label></td>' +
                    '<td><input type="text" class="hidden" name="jumlah[]" value="' + jum + '"><label class="control-label pull-right">' + jum + '</label></td>' +
                    '<td><input type="text" class="hidden" name="keterangan[]" value="' + tot + '"><label class="control-label">' + tot + '</label></td>' +
                    '<td><a onclick="$(this).parent().parent().remove();" class="btn btn-icon-only red"><i class="fa fa-times"></i></a></td>' +
                    '</tr>');
                    $('[name=jum1]').val('');
                    $('[name=jenis1]').val('');
                    $('[name=sat1]').val('');
                    $('[name=ktrg1]').val('');
                    $('[name=addbrg1]').val('');
                    $('.select').selectpicker('refresh');
                }
            });
            $('[name=addbrg]').change(function () {
                //alert('asdasd');
                var id = $(this).val();
                $.ajax({
                    url: '{{route('get-stok-alo')}}',
                    type: 'POST',
                    data: {kode: id, tipe: 'hp'},
                    dataType: 'json',
                    success: function (result) {
                        $('[name=sat]').val(result.brg['id_satuan'] + '-' + result.brg['nama_satuan']);
                        $('[name=jum]').attr({'max': result.brg['tot']});
                    }
                })
            });
            $('[name=addbrg1]').change(function () {
                //alert('asdasd');
                var id = $(this).val();
                $.ajax({
                    url: '{{route('get-stok-alo')}}',
                    type: 'POST',
                    data: {kode: id, tipe: 'aset'},
                    dataType: 'json',
                    success: function (result) {
                        $('[name=sat1]').val(result.brg['satuan']);
                        $('[name=jenis1]').val(result.brg['tipe_aset']);
                        $('[name=jum1]').attr({'max': result.brg['jumlah']});
                    }
                })
            });
            $('[id^=jum]').keyup(function () {
                //alert('as');
                var tot = parseInt($(this).val());
                var maks = parseInt(parseInt($(this).attr('max')));
                if (tot > maks) {
                    alert('Stok tersisa hanya tinggal ' + maks);
                    $(this).val(maks);
                }

            });
            $('input.form-control').keypress(function (event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                }
            });
        });
        function pop_editt(id) {
            var kode = id;
            $('select.form-control').val('-');
            $('input.form-control').val('');
            $('#body-table').empty();
            panel_refresh($('#bodi'), 'shown');
            $.ajax({
                url: '{{route('get-aset-keluar')}}',
                type: 'POST',
                data: {kode: kode},
                dataType: 'json',
                success: function (result) {
                    //var date = new Date();
                    $('[name=nmr]').val(kode);
                    $('[name=edit]').val('1');
                    $('[name=tgl]').val(result.data['tanggal']);
                    $('[name=unit]').val(result.data['id_unit'] + '-' + result.data['nama_unit']);
                    $('[name=ket]').val(result.data['keterangan']);
                    $('#tujuan').val(result.data['tujuan']);
                    $('.select').selectpicker('refresh');
                    $('#tujuan').change();
                    for (i = 0; i < result.dtl.length; i++) {
                        $('#body-table').append('<tr class="success" style="font-size: 10pt">' +
                        '<td><input type="text" class="hidden" readonly name="barang[]" value="' + result.dtl[i]['kode'] + '"><label class="control-label">' + result.dtl[i]['nama'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="jenis[]" value="' + result.dtl[i]['jenis'] + '"><label class="control-label">' + result.dtl[i]['jenis'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="satuan[]" value="' + result.dtl[i]['satuan'] + '"><label class="control-label">' + result.dtl[i]['satuan'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="jumlah[]" value="' + result.dtl[i]['jumlah'] + '"><label class="control-label pull-right">' + result.dtl[i]['jumlah'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="keterangan[]" value="' + result.dtl[i]['keterangan'] + '"><label class="control-label">' + result.dtl[i]['keterangan'] + '</label></td>' +
                        '<td><a onclick="$(this).parent().parent().remove();" class="btn btn-icon-only red"><i class="fa fa-times"></i></a></td>' +
                        '</tr>');
                    }
                    panel_refresh($('#bodi'), 'hidden');
                }

            });
            $('.select').selectpicker('refresh');
            $("#daftar").removeClass("panel-toggled");
            $("#tabel").addClass("panel-toggled");
        }
        function pop_view(id) {
            //alert(id);
            $('#detail').empty();
            $.ajax({
                url: '{{route('dkk-get-detail')}}',
                type: 'POST',
                data: {kode: id, tipe: 'ak'},
                dataType: 'json',
                success: function (result) {
                    for (i = 0; i < result.data.length; i++) {
                        $('#detail').append('<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + result.data[i]['id_barang'] + '</td>' +
                        '<td>' + result.data[i]['nama_barang'] + '</td>' +
                        '<td>' + result.data[i]['nama_satuan'] + '</td>' +
                        '<td>' + result.data[i]['jumlah'] + '</td>' +
                        '<td>' + result.data[i]['harga'] + '</td>' +
                        '</tr>');
                    }
                }
            })
            $('#modal_view').modal('show');

        }

        function del(id) {
            //$('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'deletepkm/pkm_alokasi/' + id;
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
        function getFormattedDate(date) {
            var year = date.getFullYear();
            var month = (1 + date.getMonth()).toString();
            month = month.length > 1 ? month : '0' + month;
            var day = date.getDate().toString();
            day = day.length > 1 ? day : '0' + day;
            return year + '-' + month + '-' + day;
        }
    </script>

@stop