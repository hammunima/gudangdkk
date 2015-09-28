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
        <li class="active">Penerimaan Aset</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data Penerimaan Aset</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" onclick="ReloadTable();" class="panel-refresh"><span
                                            class="fa fa-refresh"></span></a></li>
                        </ul>
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
        <!-- MODALS Penerimaan-->
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post" action="{{route('aset-masuk')}}">
                    <div class="panel panel-default @if(!$errors->has()) panel-toggled @endif" id="daftar">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Master Penerimaan Aset</strong></h3>
                            <ul class="panel-controls">
                                <li><a href="#" onclick="ReloadTable();" class="panel-refresh"><span
                                                class="fa fa-refresh"></span></a></li>
                            </ul>
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
                                <label class="col-md-2 col-xs-12 control-label">Jenis Penerimaan</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" name="trm">
                                        <option value="">Pilih Jenis Penerimaan</option>
                                        <option value="transfer masuk">Transfer Masuk</option>
                                        <option value="inventarisasi">Inventarisasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Supplier</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="supplier">
                                        <option value="0000-"></option>
                                        @foreach($m['sup'] as $row)
                                            <option value="{{$row->kode.'-'.$row->nama_supplier}}">{{$row->kode.'-'.$row->nama_supplier}}</option>
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
                                <label class="col-md-2 col-xs-12 control-label">Unit/Ruangan</label>

                                <div class="col-md-7 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="bidang">
                                        <option value="0000-"></option>
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

                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Aset</label>

                                <div class="col-md-7 col-xs-12">
                                    <div class="btn-group" id="aset">
                                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
                                            Tambah Aset
                                            <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#modal_tanah">Tanah</a>
                                            </li>
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#modal_mesin">Peralatan
                                                    dan Mesin</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_bangunan">Gedung dan
                                                    Bangunan</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_jalan">Jalan Irigasi
                                                    dan Jaringan</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_tetaplain">Aset
                                                    Tetap Lainnya</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_lain">Aset
                                                    Lainnya</a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Golongan Aset</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Harga Satuan</th>
                                    <th></th>
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
        <!--Laporan-->
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" id="repform" method="post" action="{{route('lap-terima-pkm')}}">
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
                                        <option value="id_supplier">Per Supplier</option>
                                        <option value="id_sumber">Per Sumber Anggaran</option>
                                        <option value="id_barang">Per Barang</option>
                                        <option value="tahun">Per Tahun</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group jns hidden" id="p_sup">
                                <label class="col-md-2 control-label">Supplier Barang</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_sup">
                                        <option value="">Semua Supplier</option>
                                        @foreach($m['sup'] as $row)
                                            <option value="{{$row->kode.'-'.$row->nama_supplier}}">{{$row->kode.'-'.$row->nama_supplier}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group jns hidden" id="p_sumber">
                                <label class="col-md-2 control-label">Sumber Anggaran</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_sumber">
                                        <option value="">Semua Anggaran</option>
                                        @foreach($m['sumber'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cNama}}</option>
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
                            <div class="form-group jns hidden" id="p_tahun">
                                <label class="col-md-2 control-label">Tahun Pengadaan</label>

                                <div class="col-md-5">
                                    <input type="text" name="p_tahun" onkeydown="validate_number(event);"
                                           class="form-control" placeholder="Semua"/>
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
        <!--View -->
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
                        <input name="abc" class="hidden" type="text">
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
                    <div class="modal-footer" id="foot">

                    </div>

                </div>
            </div>
        </div>
        <!--detail tanah-->
        <div class="modal" id="modal_tanah" tabindex="-1" role="dialog" aria-labelledby="largeModalHead"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="largeModalHead">Detail Tanah</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="form-tanah" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">ASET TANAH</span>
                                    <input type="text" class="hidden" name="aset" value="tanah">
                                    <input type="text" class="hidden" name="from" value="aset">
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Kode Sub Kelompok</label>

                                <div class="col-md-10 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="subkel">
                                        <option value="">Pilih Sub Kelompok</option>
                                        @foreach($m['subaset'] as $row)
                                            @if($row->kode_golongan=='01')
                                                <optgroup label="{{$row->sub_kelompok_deskripsi}}">
                                                    @foreach($m['aset'] as $rows)
                                                        @if($rows->kode_sub_kelompok==$row->kode_sub_kelompok)
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_reg"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kode Rekening</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" readonly name="kode_perwali"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="nama"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Alamat</label>

                                <div class="col-md-4 col-xs-12">
                                    <textarea rows="2" name="alamat" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Luas Tanah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="luas"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="tahun"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Atas Nama Sertifikat</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="nama_sertifikat"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Status Tanah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="status"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">No Sertifikat</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_sertifikat"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tanggal Sertifikat</label>

                                <div class="col-md-4 col-xs-12">
                                    <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                        <input type="text" class="form-control datepicker" name="tgl_sertifikat"
                                               placeholder="{{date('Y-m-d')}}" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Penggunaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="fungsi"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="asal"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="jumlah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="satuan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="h_satuan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">PPN</label>

                                <div class="col-md-4 col-xs-12">
                                    <select class="form-control select" name="ppn">
                                        <option value="0">0%</option>
                                        <option value="5">5%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="submit" class="btn btn-success" value="Simpan">
                                    <button type="button" class="btn btn-default">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--detail mesin-->
        <div class="modal" id="modal_mesin" tabindex="-1" role="dialog" aria-labelledby="largeModalHead"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="largeModalHead">Detail Peralatan dan Mesin</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="form-mesin" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">PERALATAN MESIN</span>
                                    <input type="text" class="hidden" name="aset" value="mesin">
                                    <input type="text" class="hidden" name="from" value="aset">
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Kode Sub Kelompok</label>

                                <div class="col-md-10 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="subkel">
                                        <option value="">Pilih Sub Kelompok</option>
                                        @foreach($m['subaset'] as $row)
                                            @if($row->kode_golongan=='02')
                                                <optgroup label="{{$row->sub_kelompok_deskripsi}}">
                                                    @foreach($m['aset'] as $rows)
                                                        @if($rows->kode_sub_kelompok==$row->kode_sub_kelompok)
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_reg"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" readonly name="kode_perwali"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="nama"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Nama Ruangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="ruangan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Merk</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="merk"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tipe</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="tipe"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="jumlah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="satuan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="h_satuan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">PPN</label>

                                <div class="col-md-4 col-xs-12">
                                    <select class="form-control select" name="ppn">
                                        <option value="0">0%</option>
                                        <option value="5">5%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Ukuran</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="ukuran"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Bahan Warna</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="b_warna"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">No BPKB</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_bpkb"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">No Polisi</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_polisi"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">No Rangka</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_rangka"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">No Mesin</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_mesin"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Warna</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="warna"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kapasitas Silinder</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="cc"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">No STNK</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_stnk"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tanggal STNK</label>

                                <div class="col-md-4 col-xs-12">
                                    <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                        <input type="text" class="form-control datepicker" name="tgl_stnk" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Bahan Bakar</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="bbm"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Asal Usul Perolehan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="asal"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="t_pengadaan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tahun Perakitan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="t_perakitan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nama Penanggung Jawab</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="nama_pj"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Jabatan Penanggung Jawab</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="jabatan_pj"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kondisi</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="kondisi"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="submit" class="btn btn-success" value="Simpan">
                                    <button type="button" class="btn btn-default">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--detail bangunan-->
        <div class="modal" id="modal_bangunan" tabindex="-1" role="dialog" aria-labelledby="largeModalHead"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="largeModalHead">Detail Gedung dan Bangunan</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="form-bangunan" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">GEDUNG DAN BANGUNAN</span>
                                    <input type="text" class="hidden" name="aset" value="bangunan">
                                    <input type="text" class="hidden" name="from" value="aset">
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Kode Sub Kelompok</label>

                                <div class="col-md-10 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="subkel">
                                        <option value="">Pilih Sub Kelompok</option>
                                        @foreach($m['subaset'] as $row)
                                            @if($row->kode_golongan=='03')
                                                <optgroup label="{{$row->sub_kelompok_deskripsi}}">
                                                    @foreach($m['aset'] as $rows)
                                                        @if($rows->kode_sub_kelompok==$row->kode_sub_kelompok)
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_reg"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" readonly name="kode_perwali"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="nama"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Alamat</label>

                                <div class="col-md-4 col-xs-12">
                                    <textarea rows="2" name="alamat" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Tipe</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="tipe"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="tahun"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jenis Bahan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="j_bahan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Jenis Kontruksi</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="j_kontruksi"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jumlah Lantai</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="jml_lantai"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Luas Lantai</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="l_lantai"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Luas Bangunan</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="l_bangunan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Penggunaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="fungsi"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="asal"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="jumlah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="satuan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="h_satuan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">PPN</label>

                                <div class="col-md-4 col-xs-12">
                                    <select class="form-control select" name="ppn">
                                        <option value="0">0%</option>
                                        <option value="5">5%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">No Register Tanah</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="no_reg_tanah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Luas Tanah</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="luas_tanah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Status Tanah</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="status_tanah"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Dokumen Penunjang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="dok"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Nomor Dokumen</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_dok"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kondisi</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="kondisi"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="submit" class="btn btn-success" value="Simpan">
                                    <button type="button" class="btn btn-default">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--detail jalan-->
        <div class="modal" id="modal_jalan" tabindex="-1" role="dialog" aria-labelledby="largeModalHead"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="form-jalan" id="largeModalHead">Detail Jalan Irigasi dan
                            Jaringan</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">JALAN, IRIGASI, DAN JARINGAN</span>
                                    <input type="text" class="hidden" name="aset" value="jalan">
                                    <input type="text" class="hidden" name="from" value="aset">
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Kode Sub Kelompok</label>

                                <div class="col-md-10 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="subkel">
                                        <option value="">Pilih Sub Kelompok</option>
                                        @foreach($m['subaset'] as $row)
                                            @if($row->kode_golongan=='04')
                                                <optgroup label="{{$row->sub_kelompok_deskripsi}}">
                                                    @foreach($m['aset'] as $rows)
                                                        @if($rows->kode_sub_kelompok==$row->kode_sub_kelompok)
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_reg"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="kode_perwali"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="nama"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Alamat</label>

                                <div class="col-md-4 col-xs-12">
                                    <textarea rows="2" name="alamat" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Tipe</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="luas"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="tahun"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jenis Bahan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="j_bahan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Jenis Kontruksi</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="j_kontruksi"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Panjang</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="panjang"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Lebar</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="lebar"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Luas</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="luas"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Penggunaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="fungsi"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="asal"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Dokumen Penunjang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="dok"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Nomor Dokumen</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_dok"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">No Register Tanah</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="no_reg_tanah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Luas Tanah</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="luas_tanah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Status Tanah</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="status_tanah"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="jumlah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="satuan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="h_satuan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">PPN</label>

                                <div class="col-md-4 col-xs-12">
                                    <select class="form-control select" name="ppn">
                                        <option value="0">0%</option>
                                        <option value="5">5%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kondisi</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="kondisi"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="submit" class="btn btn-success" value="Simpan">
                                    <button type="button" class="btn btn-default">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--detail tetap lain-->
        <div class="modal" id="modal_tetaplain" tabindex="-1" role="dialog" aria-labelledby="largeModalHead"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="largeModalHead">Detail Aset Tetap Lainnya</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="form-tetaplain" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">Aset Tetap Lainnya</span>
                                    <input type="text" class="hidden" name="aset" value="tetaplain">
                                    <input type="text" class="hidden" name="from" value="aset">
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Kode Sub Kelompok</label>

                                <div class="col-md-10 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="subkel">
                                        <option value="">Pilih Sub Kelompok</option>
                                        @foreach($m['subaset'] as $row)
                                            @if($row->kode_golongan=='05')
                                                <optgroup label="{{$row->sub_kelompok_deskripsi}}">
                                                    @foreach($m['aset'] as $rows)
                                                        @if($rows->kode_sub_kelompok==$row->kode_sub_kelompok)
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_reg"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="kode_perwali"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="nama"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Nama Ruangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="ruangan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Judul</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="judul"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="tahun"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Pengarang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="pengarang"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Pencipta</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="pencipta"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Asal Daerah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="daerah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Jenis</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="jenis"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Bahan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="bahan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Ukuran</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="ukuran"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Penggunaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="fungsi"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="asal"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="jumlah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="satuan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="h_satuan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">PPN</label>

                                <div class="col-md-4 col-xs-12">
                                    <select class="form-control select" name="ppn">
                                        <option value="0">0%</option>
                                        <option value="5">5%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="submit" class="btn btn-success" value="Simpan">
                                    <button type="button" class="btn btn-default">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--detail lainnya-->
        <div class="modal" id="modal_lain" tabindex="-1" role="dialog" aria-labelledby="largeModalHead"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="largeModalHead">Detail Aset Lainnya</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="form-lain" method="post" action="">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">Aset Lainnya</span>
                                    <input type="text" class="hidden" name="aset" value="lain">
                                    <input type="text" class="hidden" name="from" value="aset">
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Kode Sub Kelompok</label>

                                <div class="col-md-10 col-xs-12">
                                    <select class="form-control select" data-live-search="true" name="subkel">
                                        <option value="">Pilih Sub Kelompok</option>
                                        @foreach($m['subaset'] as $row)
                                            @if($row->kode_golongan=='07')
                                                <optgroup label="{{$row->sub_kelompok_deskripsi}}">
                                                    @foreach($m['aset'] as $rows)
                                                        @if($rows->kode_sub_kelompok==$row->kode_sub_kelompok)
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="no_reg"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="kode_perwali"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="nama"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Nama Ruangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="ruangan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Merk</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="merk"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tipe</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="tipe"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">No Seri</label>

                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control" name="no_seri"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="asal"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="tahun"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="jumlah"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="satuan"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="h_satuan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">PPN</label>

                                <div class="col-md-4 col-xs-12">
                                    <select class="form-control select" name="ppn">
                                        <option value="0">0%</option>
                                        <option value="5">5%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan"/>
                                </div>
                                <label class="col-md-2 col-xs-12 control-label">Kondisi</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="kondisi"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                <div class="col-md-4 col-xs-12">
                                    <input type="submit" class="btn btn-success" value="Simpan">
                                    <button type="button" class="btn btn-default">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                $('[name=tahun]').val('{{date('Y')}}');
                $('[name=nmr]').val('{{$m['baru']}}');
                $('[name=edit]').val('0');
                $('[name=trm]').val('');
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
            $('[name=subkel]').change(function () {
                var id = $(this).val();
                $.ajax({
                    url: '{{route('get-aset-dtl')}}',
                    type: 'POST',
                    data: {kode: id},
                    dataType: 'json',
                    success: function (result) {
                        $('[name=kode_perwali]').val(result.brg['KODE_SUB_KEL_AT']);
                        $('[name=nama]').val(result.brg['SUBSUB_KELOMPOK_DESKRIPSI']);
                    }
                })
            });
            $('[name=jns]').change(function () {
                if ($(this).val() == 'id_supplier') {
                    $('.jns').addClass('hidden');
                    $('#p_sup').removeClass('hidden');
                } else if ($(this).val() == 'id_sumber') {
                    $('.jns').addClass('hidden');
                    $('#p_sumber').removeClass('hidden');
                } else if ($(this).val() == 'id_barang') {
                    $('.jns').addClass('hidden');
                    $('#p_jb').removeClass('hidden');
                    $('#p_b').removeClass('hidden');
                } else if ($(this).val() == 'tahun') {
                    $('.jns').addClass('hidden');
                    $('#p_tahun').removeClass('hidden');
                } else {
                    $('.jns').addClass('hidden');
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
            /*$('#add').click(function () {
             var brg = $('[name=addbrg]').val();
             var jum = $('[name=jum]').val();
             var sat = $('[name=sat]').val();
             var gol = $('[name=gol]').val();
             var h_sat = $('[name=h_sat]').val();
             if (brg == '') {
             alert('Masukkan barang dahulu');
             $('[name=addbrg]').focus();
             } else if (jum == '' || jum == 0) {
             alert('Jumlah tidak boleh kosong');
             $('[name=jum]').focus();
             } else {
             $('#body-table').append('<tr class="success" style="font-size: 10pt">' +
             '<td><input type="text" class="hidden" readonly name="barang[]" value="' + brg + '"><label class="control-label">' + brg + '</label></td>' +
             '<td><input type="text" class="hidden" readonly name="gol[]" value="' + gol + '"><label class="control-label pull-right">' + gol + '</label></td>' +
             '<td><input type="text" class="hidden" readonly name="jumlah[]" value="' + jum + '"><label class="control-label pull-right">' + jum + '</label></td>' +
             '<td><input type="text" class="hidden" readonly name="satuan[]" value="' + sat + '"><label class="control-label">' + sat + '</label></td>' +
             '<td><input type="text" class="hidden" readonly name="h_sat[]" value="' + h_sat + '"><label class="control-label pull-right">' + h_sat + '</label></td>' +
             '<td><a onclick="$(this).parent().parent().remove();" class="btn btn-icon-only red"><i class="fa fa-times"></i></a></td>' +
             '</tr>');
             $('[name=jum]').val('');
             $('[name=sat]').val('');
             $('[name=gol]').val('');
             $('[name=h_sat]').val('');
             $('[name=addbrg]').val('');
             $('.select').selectpicker('refresh');
             }
             });*/

            /*$('#jum').keyup(function () {
             //alert('as');
             var tot = parseInt($('[name=jum]').val()) * parseInt($('[name=h_sat]').val());
             $('[name=tot]').val(tot);
             });
             $('[name=h_sat]').keyup(function () {
             //alert('as');
             var tot = parseInt($('[name=jum]').val()) * parseInt($('[name=h_sat]').val());
             $('[name=tot]').val(tot);
             });*/
            $('input.form-control').keypress(function (event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                }
            });
            //form tanah
            $('[id^=form]').on('submit', function (e) {
                e.preventDefault();
                var f = $(this).attr('id');
                var brg = $('#' + f + ' input[name=nama]').val();
                var jum = $('#' + f + ' input[name=jumlah]').val();
                var sat = $('#' + f + ' input[name=satuan]').val();
                var gol = $('#' + f + ' input[name=aset]').val();
                var h_sat = $('#' + f + ' input[name=h_satuan]').val();
                $.ajax({
                    type: 'post',
                    url: '{{route('entry-aset')}}',
                    data: $('#' + f).serialize(),
                    success: function (result) {
                        //alert('form was submitted');
                        $('#body-table').append('<tr class="success" style="font-size: 10pt">' +
                        '<td><input type="text" class="hidden" readonly name="barang[]" value="' + result.id + '"><label class="control-label">' + brg + '</label></td>' +
                        '<td><label class="control-label">' + gol + '</label></td>' +
                        '<td><label class="control-label pull-right">' + jum + '</label></td>' +
                        '<td><label class="control-label">' + sat + '</label></td>' +
                        '<td><label class="control-label pull-right">' + h_sat + '</label></td>' +
                        '<td><a onclick="$(this).parent().parent().remove();" class="btn btn-icon-only red"><i class="fa fa-times"></i></a></td>' +
                        '</tr>');
                    }
                });
                $('#' + f + ' input[name=aset]').val(gol);
                $('#' + f + ' select[name=subkel]').val('');
                $('#' + f + ' input.form-control').val('');
                $('.select').selectpicker('refresh');
                $('#modal_' + gol).modal('hide');
            });
        });
        function pop_editt(id) {
            var kode = id;
            $('select.form-control').val('0000-');
            $('input.form-control').val('');
            $('#body-table').empty();
            $.ajax({
                url: '{{route('get-aset-masuk')}}',
                type: 'POST',
                data: {kode: kode},
                dataType: 'json',
                success: function (result) {
                    var a = '0000';
                    var b = '0000';
                    var c = '0000';
                    if (result.data['id_supplier'] != 0) {
                        a = result.data['id_supplier'];
                    }
                    if (result.data['id_sumber'] != 0) {
                        b = result.data['id_sumber'];
                    }
                    if (result.data['id_unit'] != 0) {
                        c = result.data['id_unit'];
                    }
                    $('[name=nmr]').val(kode);
                    $('[name=edit]').val('1');
                    $('[name=tgl]').val(result.data['tanggal']);
                    $('[name=trm]').val(result.data['jenis']);
                    $('[name=supplier]').val(a + '-' + result.data['nama_supplier']);
                    $('[name=sumber]').val(b + '-' + result.data['nama_sumber']);
                    $('[name=bidang]').val(c + '-' + result.data['nama_unit']);
                    $('[name=tahun]').val(result.data['tahun']);
                    $('[name=ket]').val(result.data['keterangan']);
                    $('[name=nmr_bukti]').val(result.data['no_bukti']);
                    $('[name=tgl_bukti]').val(result.data['tgl_bukti']);
                    $('[name=nmr_surat]').val(result.data['no_surat']);
                    $('[name=jenis_surat]').val(result.data['jenis_surat']);
                    $('[name=tgl_surat]').val(result.data['tgl_surat']);
                    $('.select').selectpicker('refresh');
                    for (i = 0; i < result.dtl.length; i++) {
                        $('#body-table').append('<tr class="success" style="font-size: 10pt">' +
                        '<td><input type="text" class="hidden" readonly name="barang[]" value="' + result.dtl[i]['id'] + '"><label class="control-label">' + result.dtl[i]['nama'] + '</label></td>' +
                        '<td><label class="control-label">' + result.dtl[i]['tipe_aset'] + '</label></td>' +
                        '<td><label class="control-label pull-right">' + result.dtl[i]['jumlah'] + '</label></td>' +
                        '<td><label class="control-label">' + result.dtl[i]['satuan'] + '</label></td>' +
                        '<td><label class="control-label pull-right">' + result.dtl[i]['h_satuan'] + '</label></td>' +
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
            $('#foot').empty();
            $('[name=abc]').val(id);
            $.ajax({
                url: '{{route('dkk-get-detail')}}',
                type: 'POST',
                data: {kode: id, tipe: 'am'},
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
                    if (result.read[0]['read'] == '1') {
                        $('#foot').append('<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>' +
                        '<a onclick="read()"><button type="button" class="btn btn-success pull-right" data-dismiss="modal">Terima</button></a>');
                    } else {
                        $('#foot').append('<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>');
                    }
                }
            })
            $('#modal_view').modal('show');
        }
        function read() {
            //alert('aa');
            var a = $('[name=abc]').val();
            $.ajax({
                url: '{{route('dkk-validate')}}',
                type: 'POST',
                data: {kode: a, tipe: 'read'},
                dataType: 'json',
                success: function (result) {
                    if (result.data == 'sukses') {
                        $('.datatable').DataTable().ajax.reload();
                        alert('Barang telah diterima');
                    }
                }
            });
        }
        function lock(id) {
            //alert(id);
            //$('.datatable').DataTable().ajax.reload();
            noty({
                text: 'Apakah anda yakin?',
                layout: 'topRight',
                buttons: [
                    {
                        addClass: 'btn btn-success btn-clean', text: 'Ok',
                        onClick: function ($noty) {
                            $noty.close();
                            $.ajax({
                                url: '{{route('dkk-validate')}}',
                                type: 'POST',
                                data: {kode: id, tipe: 'lock'},
                                dataType: 'json',
                                success: function (result) {
                                    if (result.data == 'sukses') {
                                        $('.datatable').DataTable().ajax.reload();
                                    }
                                }
                            });

                        }
                    },
                    {
                        addClass: 'btn btn-danger btn-clean', text: 'Cancel', onClick: function ($noty) {
                        $noty.close();
                        alert('1234');
                        //noty({text: 'You clicked "Cancel" button', layout: 'topRight', type: 'error'});
                    }
                    }
                ]
            })
        }
        function unlock(id) {
            noty({
                text: 'Apakah anda yakin?',
                layout: 'topRight',
                buttons: [
                    {
                        addClass: 'btn btn-success btn-clean', text: 'Ok',
                        onClick: function ($noty) {
                            $noty.close();
                            $.ajax({
                                url: '{{route('dkk-validate')}}',
                                type: 'POST',
                                data: {kode: id, tipe: 'unlock'},
                                dataType: 'json',
                                success: function (result) {
                                    if (result.data == 'sukses') {
                                        $('.datatable').DataTable().ajax.reload();
                                    }
                                }
                            })
                        }
                    },
                    {
                        addClass: 'btn btn-danger btn-clean', text: 'Cancel', onClick: function ($noty) {
                        $noty.close();
                        //noty({text: 'You clicked "Cancel" button', layout: 'topRight', type: 'error'});
                    }
                    }
                ]
            })
        }
        function ReloadTable() {
            $('.datatable').DataTable().ajax.reload();
        }

        function del(id) {
            $('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'deletepkm/pkm_masuk/' + id;
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