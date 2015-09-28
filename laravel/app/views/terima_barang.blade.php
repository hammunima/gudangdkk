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
        <li class="active">Penerimaan</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data Penerimaan</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <button id="tambah" class="btn btn-primary btn-rounded">
                                        Tambah Penerimaan
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
                <form class="form-horizontal" method="post" action="{{route('m-penerimaan')}}">
                    <div class="panel panel-default @if(!$errors->has()) panel-toggled @endif" id="daftar">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Master Penerimaan</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nomor Penerimaan</label>

                                        <div class="col-md-9">
                                            <input type="text" name="nmr" class="form-control" readonly/>
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
                                <label class="col-md-2 col-xs-12 control-label">Supplier</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="supplier">
                                        <option value="0000-"></option>
                                        @foreach($m['sup'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cKode.'-'.$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Sumber Anggaran</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="sumber">
                                        <option value="0000-"></option>
                                        @foreach($m['sumber'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cKode.'-'.$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Bidang Pengadaan</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="bidang">
                                        <option value="0000-"></option>
                                        @foreach($m['bidang'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cKode.'-'.$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                <div class="col-md-7 col-xs-12">
                                    <input type="text" name="tahun" onkeydown="validate_number(event);"
                                           class="form-control"/>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-7 col-xs-12">
                                    <input type="text" name="ket" class="form-control"/>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2"><h4 style="font-weight: bold">Bukti
                                        Penerimaan</h4></label>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Nomor Bukti</label>

                                        <div class="col-md-8 col-xs-12">
                                            <input type="text" name="nmr_bukti" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal</label>

                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                                <input type="text" class="form-control datepicker" name="tgl_bukti"
                                                       value="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label">&nbsp;</label>

                            <div class="form-group">
                                <label class="control-label col-md-2"><h4 style="font-weight: bold">Dasar
                                        Penerimaan</h4></label>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Jenis Surat</label>

                                        <div class="col-md-8 col-xs-12">
                                            <input type="text" name="jenis_surat" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-5 control-label">Nomor Surat</label>

                                        <div class="col-md-7 col-xs-12">
                                            <input type="text" name="nmr_surat" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal</label>

                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                                <input type="text" class="form-control datepicker" name="tgl_surat"
                                                       value="{{date('Y-m-d')}} ">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label">&nbsp;</label>

                            <div class="form-group">
                                <label class="control-label col-md-2"><h4 style="font-weight: bold">Berita Acara</h4>
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Nomor Acara</label>

                                        <div class="col-md-8 col-xs-12">
                                            <input type="text" name="nmr_acara" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Tanggal</label>

                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                                <input type="text" class="form-control datepicker" name="tgl_acara"
                                                       value="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="control-label">&nbsp;</label>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control select" data-live-search="true" name="addbrg">
                                            <option value=""></option>
                                            @foreach($m['brg'] as $row)
                                                <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cKode.' - '.$row->cNama}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="jum" name="jum" onkeydown="validate_number(event);"
                                               class="form-control" style="text-align: right">
                                    </td>
                                    <td><input type="text" name="sat" readonly class="form-control"></td>
                                    <td>
                                        <input type="text" name="h_sat" onkeydown="validate_number(event);"
                                               class="form-control" style="text-align: right">
                                    </td>
                                    <td>
                                        <input type="text" name="tot" readonly class="form-control"
                                               style="text-align: right">
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
                <form class="form-horizontal" id="repform" method="post" action="{{route('report-trm')}}">
                    <div class="panel panel-default panel-toggled " id="report">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Laporan Penerimaan Barang</strong></h3>
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
                                        <option value="cKdSupplier">Per Supplier</option>
                                        <option value="cKdSumber">Per Sumber Anggaran</option>
                                        <option value="cTahunPengadaan">Per Tahun Pengadaan</option>
                                        <option value="cKode">Per Barang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group jns hidden" id="p_sup">
                                <label class="col-md-2 control-label">Supplier Barang</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_sup">
                                        <option value="0">Semua Supplier</option>
                                        @foreach($m['sup'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group jns hidden" id="p_sumber">
                                <label class="col-md-2 control-label">Sumber Anggaran</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_sumber">
                                        <option value="0">Semua Anggaran</option>
                                        @foreach($m['sumber'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group jns hidden" id="tahun">
                                <label class="col-md-2 control-label">Tahun Pengadaan</label>

                                <div class="col-md-5">
                                    <input type="text" name="p_tahun" onkeydown="validate_number(event);"
                                           class="form-control" placeholder="Semua"/>
                                </div>
                            </div>
                            <div class="form-group jns hidden" id="p_brg">
                                <label class="col-md-2 control-label">Barang</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_barang">
                                        <option value="0">-Semua Barang-</option>
                                        @foreach($m['brg'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cKode.'-'.$row->cNama}}</option>
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
                                <th>Harga Total</th>
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
                $('[name=edit]').val('0');
                $('select.form-control').val('0000-');
                $('#body-table').empty();
                $('.select').selectpicker('refresh');
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
            $('[name=jns').change(function () {
                if ($(this).val() == 'cKdSupplier') {
                    $('.jns').addClass('hidden');
                    $("[name=p_sup]").val(0);
                    $('#p_sup').removeClass('hidden');
                } else if ($(this).val() == 'cKdSumber') {
                    $('.jns').addClass('hidden');
                    $("[name=p_sumber]").val(0);
                    $('#p_sumber').removeClass('hidden');
                } else if ($(this).val() == 'cTahunPengadaan') {
                    $('.jns').addClass('hidden');
                    $("[name=p_tahun]").val('');
                    $('#tahun').removeClass('hidden');
                } else if ($(this).val() == 'cKode') {
                    $('.jns').addClass('hidden');
                    $("[name=p_barang]").val(0);
                    $('#p_brg').removeClass('hidden');
                } else {
                    $("[name=p_sup]").val(0);
                    $("[name=p_sumber]").val(0);
                    $("[name=p_tahun]").val('');
                    $("[name=p_barang]").val(0);
                    $('.jns').addClass('hidden');
                }
                $('.select').selectpicker('refresh');
            });
            $('#add').click(function () {
                var brg = $('[name=addbrg]').val();
                var jum = $('[name=jum]').val();
                var sat = $('[name=sat]').val();
                var h_sat = $('[name=h_sat]').val();
                var tot = $('[name=tot]').val();
                if (brg == '') {
                    alert('Masukkan barang dahulu');
                    $('[name=addbrg]').focus();
                } else if (jum == '' || jum == 0) {
                    alert('Jumlah tidak boleh kosong');
                    $('[name=jum]').focus();
                } else {
                    $('#body-table').append('<tr class="success" style="font-size: 10pt">' +
                    '<td><input type="text" class="hidden" readonly name="barang[]" value="' + brg + '"><label class="control-label">' + brg + '</label></td>' +
                    '<td><input type="text" class="hidden" readonly name="jumlah[]" value="' + jum + '"><label class="control-label pull-right">' + jum + '</label></td>' +
                    '<td><input type="text" class="hidden" readonly name="satuan[]" value="' + sat + '"><label class="control-label">' + sat + '</label></td>' +
                    '<td><input type="text" class="hidden" readonly name="h_satuan[]" value="' + h_sat + '"><label class="control-label pull-right">' + h_sat + '</label></td>' +
                    '<td><input type="text" class="hidden" readonly name="total[]" value="' + tot + '"><label class="control-label pull-right">' + tot + '</label></td>' +
                    '<td><a onclick="$(this).parent().parent().remove();" class="btn btn-icon-only red"><i class="fa fa-times"></i></a></td>' +
                    '</tr>');
                    $('[name=jum]').val('');
                    $('[name=sat]').val('');
                    $('[name=h_sat]').val('');
                    $('[name=tot]').val('');
                    $('[name=addbrg]').val('');
                    $('.select').selectpicker('refresh');
                }
            });
            $('[name=addbrg]').change(function () {
                //alert('asdasd');
                var id = $(this).val();
                $.ajax({
                    url: '{{route('get-brg')}}',
                    type: 'POST',
                    data: {kode: id},
                    dataType: 'json',
                    success: function (result) {
                        $('[name=sat]').val(result.brg['cKdSatuan'] + '-' + result.brg['cSatuan']);
                        $('[name=h_sat]').val(result.brg['nHarga']);
                    }
                })
            });
            $('#jum').keyup(function () {
                //alert('as');
                var tot = parseInt($('[name=jum]').val()) * parseInt($('[name=h_sat]').val());
                $('[name=tot]').val(tot);
            });
            $('[name=h_sat]').keyup(function () {
                //alert('as');
                var tot = parseInt($('[name=jum]').val()) * parseInt($('[name=h_sat]').val());
                $('[name=tot]').val(tot);
            });
            $('input.form-control').keypress(function (event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                }
            });
        });
        function pop_editt(id) {
            var kode = 'TRM-' + id;
            $('select.form-control').val('0000-');
            $('input.form-control').val('');
            $('#body-table').empty();
            $.ajax({
                url: '{{route('get-trm')}}',
                type: 'POST',
                data: {kode: kode},
                dataType: 'json',
                success: function (result) {
                    var a = '0000';
                    var b = '0000';
                    var c = '0000';
                    if (result.data['cKdSupplier'] != 0) {
                        a = result.data['cKdSupplier'];
                    }
                    if (result.data['cKdSumber'] != 0) {
                        b = result.data['cKdSumber'];
                    }
                    if (result.data['cKdBidang'] != 0) {
                        c = result.data['cKdBidang'];
                    }
                    $('[name=nmr]').val(kode);
                    $('[name=edit]').val('1');
                    $('[name=tgl]').val(result.data['dTanggal']);
                    $('[name=supplier]').val(a + '-' + result.data['cSupplier']);
                    $('[name=sumber]').val(b + '-' + result.data['cSumber']);
                    $('[name=bidang]').val(c + '-' + result.data['cBidang']);
                    $('[name=tahun]').val(result.data['cTahunPengadaan']);
                    $('[name=ket]').val(result.data['cKeterangan']);
                    $('[name=nmr_bukti]').val(result.data['cNoBukti']);
                    $('[name=tgl_bukti]').val(result.data['dTglBukti']);
                    $('[name=nmr_surat]').val(result.data['cNoSurat']);
                    $('[name=jenis_surat]').val(result.data['cJnsSurat']);
                    $('[name=tgl_surat]').val(result.data['dTglSurat']);
                    $('.select').selectpicker('refresh');
                    for (i = 0; i < result.dtl.length; i++) {
                        $('#body-table').append('<tr class="success" style="font-size: 10pt">' +
                        '<td><input type="text" class="hidden" readonly name="barang[]" value="' + result.dtl[i]['cKode'] + '-' + result.dtl[i]['cNama'] + '"><label class="control-label">' + result.dtl[i]['cKode'] + '-' + result.dtl[i]['cNama'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="jumlah[]" value="' + result.dtl[i]['nQty'] + '"><label class="control-label pull-right">' + result.dtl[i]['nQty'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="satuan[]" value="' + result.dtl[i]['cKdSatuan'] + '-' + result.dtl[i]['cSatuan'] + '"><label class="control-label">' + result.dtl[i]['cKdSatuan'] + '-' + result.dtl[i]['cSatuan'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="h_satuan[]" value="' + result.dtl[i]['nHarga'] + '"><label class="control-label pull-right">' + result.dtl[i]['nHarga'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="total[]" value="' + result.dtl[i]['nSTotal'] + '"><label class="control-label pull-right">' + result.dtl[i]['nSTotal'] + '</label></td>' +
                        '<td><a onclick="$(this).parent().parent().remove();" class="btn btn-icon-only red"><i class="fa fa-times"></i></a></td>' +
                        '</tr>');
                    }
                }

            })
            $("#daftar").removeClass("panel-toggled");
            $("#tabel").addClass("panel-toggled");
        }
        function pop_view(id) {
            //alert(id);
            $('#detail').empty();
            $.ajax({
                url: '{{route('get-detail')}}',
                type: 'POST',
                data: {kode: id, tipe: 'terima'},
                dataType: 'json',
                success: function (result) {
                    for (i = 0; i < result.data.length; i++) {
                        $('#detail').append('<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + result.data[i]['cKode'] + '</td>' +
                        '<td>' + result.data[i]['cNama'] + '</td>' +
                        '<td>' + result.data[i]['cSatuan'] + '</td>' +
                        '<td>' + result.data[i]['nQty'] + '</td>' +
                        '<td>' + result.data[i]['nSTotal'] + '</td>' +
                        '</tr>');
                    }
                }
            })
            $('#modal_view').modal('show');

        }
        function del(id) {
            $('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'delete/tbterima/' + id;
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