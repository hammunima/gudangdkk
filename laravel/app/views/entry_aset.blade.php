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
        <li><a href="#">Aset</a></li>
        <li class="active">Entry Aset</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#tab-first" role="tab" data-toggle="tab">Tanah</a></li>
                        <li><a href="#tab-second" role="tab" data-toggle="tab">Peralatan dan Mesin</a></li>
                        <li><a href="#tab-third" role="tab" data-toggle="tab">Gedung dan Bangunan</a></li>
                        <li><a href="#tab-fourth" role="tab" data-toggle="tab">Jalan, Irigasi dan Jaringan</a></li>
                        <li><a href="#tab-fifth" role="tab" data-toggle="tab">Aset Tetap Lainnya</a></li>
                        <li><a href="#tab-sixth" role="tab" data-toggle="tab">Aset Lainnya</a></li>
                    </ul>
                    <div class="panel-body tab-content">
                        <div class="tab-pane active" id="tab-first">
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label class="control-label col-md-10 pull-right">
                                        <span class="label label-default label-form pull-right">ASET TANAH</span>
                                        <input type="text" class="hidden" name="aset" value="tanah">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Kode Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_bidang"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Deskripsi Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="desc_bidang"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_reg"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_perwali"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="nama"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Alamat</label>

                                    <div class="col-md-3 col-xs-12">
                                        <textarea rows="2" name="alamat" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Luas Tanah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="luas"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="tahun"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Atas Nama Sertifikat</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="nama_sertifikat"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Status Tanah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="status"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">No Sertifikat</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_sertifikat"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tanggal Sertifikat</label>

                                    <div class="col-md-3 col-xs-12">
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

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="fungsi"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="asal"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="jumlah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="satuan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_satuan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Harga Total + PPN</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_total"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="keterangan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="submit" class="btn btn-success" value="Simpan">
                                        <button type="button" class="btn btn-default">Batal</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane" id="tab-second">
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label class="control-label col-md-10 pull-right">
                                        <span class="label label-default label-form pull-right">PERALATAN MESIN</span>
                                        <input type="text" class="hidden" name="aset" value="mesin">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Kode Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_bidang"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Deskripsi Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="desc_bidang"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_reg"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_perwali"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="nama"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Nama Ruangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="ruangan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Merk</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="merk"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tipe</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="tipe"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="jumlah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="satuan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_satuan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Harga Total + PPN</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_total"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Ukuran</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="ukuran"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Bahan Warna</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="b_warna"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">No BPKB</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_bpkb"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">No Polisi</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_polisi"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">No Rangka</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_rangka"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">No Mesin</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_mesin"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Warna</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="warna"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kapasitas Silinder</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="cc"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">No STNK</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_stnk"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tanggal STNK</label>

                                    <div class="col-md-3 col-xs-12">
                                        <div class="input-group">
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            <input type="text" class="form-control datepicker" name="tgl_stnk" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Bahan Bakar</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="bbm"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Asal Usul Perolehan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="asal"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="t_pengadaan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tahun Perakitan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="t_perakitan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nama Penanggung Jawab</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="nama_pj"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Jabatan Penanggung Jawab</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="jabatan_pj"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="keterangan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kondisi</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kondisi"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="submit" class="btn btn-success" value="Simpan">
                                        <button type="button" class="btn btn-default">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab-third">
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label class="control-label col-md-10 pull-right">
                                        <span class="label label-default label-form pull-right">GEDUNG DAN BANGUNAN</span>
                                        <input type="text" class="hidden" name="aset" value="bangunan">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Kode Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_bidang"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Deskripsi Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="desc_bidang"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_reg"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_perwali"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="nama"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Alamat</label>

                                    <div class="col-md-3 col-xs-12">
                                        <textarea rows="2" name="alamat" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Tipe</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="luas"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="tahun"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jenis Bahan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="j_bahan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Jenis Kontruksi</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="j_kontruksi"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jumlah Lantai</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="jml_lantai"/>
                                    </div>
                                    <label class="col-md-3 col-xs-12 control-label">Luas Lantai</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="l_lantai"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Luas Bangunan</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="l_bangunan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Penggunaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="fungsi"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="asal"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="jumlah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="satuan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_satuan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Harga Total + PPN</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_total"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">No Register Tanah</label>

                                    <div class="col-md-2 col-xs-12">
                                        <input type="text" class="form-control" name="no_reg_tanah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Luas Tanah</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="luas_tanah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Status Tanah</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="status_tanah"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Dokumen Penunjang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="dok"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Nomor Dokumen</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_dok"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="keterangan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kondisi</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kondisi"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="submit" class="btn btn-success" value="Simpan">
                                        <button type="button" class="btn btn-default">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab-fourth">
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label class="control-label col-md-10 pull-right">
                                        <span class="label label-default label-form pull-right">JALAN, IRIGASI, DAN JARINGAN</span>
                                        <input type="text" class="hidden" name="aset" value="jalan">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Kode Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_bidang"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Deskripsi Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="desc_bidang"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_reg"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_perwali"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="nama"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Alamat</label>

                                    <div class="col-md-3 col-xs-12">
                                        <textarea rows="2" name="alamat" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Tipe</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="luas"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="tahun"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jenis Bahan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="j_bahan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Jenis Kontruksi</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="j_kontruksi"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Panjang</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="panjang"/>
                                    </div>
                                    <label class="col-md-3 col-xs-12 control-label">Lebar</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="lebar"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Luas</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="luas"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Penggunaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="fungsi"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="asal"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Dokumen Penunjang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="dok"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Nomor Dokumen</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_dok"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">No Register Tanah</label>

                                    <div class="col-md-2 col-xs-12">
                                        <input type="text" class="form-control" name="no_reg_tanah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Luas Tanah</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="luas_tanah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Status Tanah</label>

                                    <div class="col-md-1 col-xs-12">
                                        <input type="text" class="form-control" name="status_tanah"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="jumlah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="satuan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_satuan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Harga Total + PPN</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_total"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="keterangan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kondisi</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kondisi"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="submit" class="btn btn-success" value="Simpan">
                                        <button type="button" class="btn btn-default">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab-fifth">
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label class="control-label col-md-10 pull-right">
                                        <span class="label label-default label-form pull-right">Aset Tetap Lainnya</span>
                                        <input type="text" class="hidden" name="aset" value="tetaplain">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Kode Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_bidang"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Deskripsi Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="desc_bidang"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_reg"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_perwali"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="nama"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Nama Ruangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="ruangan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Judul</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="judul"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="tahun"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Pengarang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="pengarang"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Pencipta</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="pencipta"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Asal Daerah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="daerah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Jenis</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="jenis"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Bahan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="bahan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Ukuran</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="ukuran"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Penggunaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="fungsi"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="asal"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="jumlah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="satuan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_satuan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Harga Total + PPN</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_total"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="keterangan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="submit" class="btn btn-success" value="Simpan">
                                        <button type="button" class="btn btn-default">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab-sixth">
                            <form class="form-horizontal" method="post" action="">
                                <div class="form-group">
                                    <label class="control-label col-md-10 pull-right">
                                        <span class="label label-default label-form pull-right">Aset Lainnya</span>
                                        <input type="text" class="hidden" name="aset" value="lain">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Kode Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_bidang"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Deskripsi Bidang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="desc_bidang"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nomor Register</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="no_reg"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kode Barang Perwali</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kode_perwali"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Nama Barang</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="nama"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Nama Ruangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="ruangan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Merk</label>

                                    <div class="col-md-2 col-xs-12">
                                        <input type="text" class="form-control" name="merk"/>
                                    </div>
                                    <label class="col-md-1 col-xs-12 control-label">Tipe</label>

                                    <div class="col-md-2 col-xs-12">
                                        <input type="text" class="form-control" name="tipe"/>
                                    </div>
                                    <label class="col-md-1 col-xs-12 control-label">No Seri</label>

                                    <div class="col-md-2 col-xs-12">
                                        <input type="text" class="form-control" name="no_seri"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Asal Usul</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="asal"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Tahun Pengadaan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="tahun"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Jumlah</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="jumlah"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="satuan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Harga Satuan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_satuan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Harga Total + PPN</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="h_total"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">Keterangan</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="keterangan"/>
                                    </div>
                                    <label class="col-md-2 col-xs-12 control-label">Kondisi</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="text" class="form-control" name="kondisi"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 col-xs-12 control-label">&nbsp;</label>

                                    <div class="col-md-3 col-xs-12">
                                        <input type="submit" class="btn btn-success" value="Simpan">
                                        <button type="button" class="btn btn-default">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="panel-footer">
                    </div>
                </div>


            </div>
        </div>

    </div>
@stop
@section('ajax')

    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-datepicker.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#tambah").click(function () {
                $('[name=id]').val('');
                $('[name=nama]').val('');
                $('#modal_basic').modal('show');
            });
        });
        function pop_edit(id1) {
            $('[name=id]').val(id1);
            $.ajax({
                url: '{{route('get-nama')}}',
                type: 'POST',
                data: {kode: id1, tabel: 'tbjenis'},
                dataType: 'json',
                success: function (result) {
                    $('[name=nama]').val(result.data['cNama']);
                }

            })
            //$('[name=nama]').val(tes);
            $('#modal_basic').modal('show');
        }
        function del(id) {
            $('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'delete/jenis/' + id;
            }
            //$('[name=nama]').val(tes);
        }
    </script>

@stop