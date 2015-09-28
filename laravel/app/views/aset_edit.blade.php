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
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-md-2">Daftar Kode Aset</label>

                                <div class="col-md-6">
                                    <select class="form-control select" data-live-search="true" id="list">
                                        <option value="">Pilih Aset</option>
                                        @foreach($aset as $row)
                                            <option value="{{$row->id_aset}}">{{$row->id_aset}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">&nbsp;</label>

                                <div class="col-md-1">
                                    <input data-toggle="modal" data-target="" type="button" id="edt"
                                           class="btn btn-primary" value="Edit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END DEFAULT DATATABLE -->

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
                        <form class="form-horizontal" id="form-tanah" method="post" action="{{route('aset-edit')}}">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">ASET TANAH</span>
                                    <input type="text" class="hidden" name="aset" value="tanah">
                                    <input type="text" class="hidden" name="ast" value="">
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
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'#'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
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
                        <form class="form-horizontal" id="form-mesin" method="post" action="{{route('aset-edit')}}">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">PERALATAN MESIN</span>
                                    <input type="text" class="hidden" name="aset" value="mesin">
                                    <input type="text" class="hidden" name="ast" value="">
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
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'#'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
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
                        <form class="form-horizontal" id="form-bangunan" method="post" action="{{route('aset-edit')}}">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">GEDUNG DAN BANGUNAN</span>
                                    <input type="text" class="hidden" name="aset" value="bangunan">
                                    <input type="text" class="hidden" name="ast" value="">
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
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'#'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
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
                        <form class="form-horizontal" method="post" action="{{route('aset-edit')}}">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">JALAN, IRIGASI, DAN JARINGAN</span>
                                    <input type="text" class="hidden" name="aset" value="jalan">
                                    <input type="text" class="hidden" name="ast" value="">
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
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'#'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
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
                        <form class="form-horizontal" id="form-tetaplain" method="post" action="{{route('aset-edit')}}">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">Aset Tetap Lainnya</span>
                                    <input type="text" class="hidden" name="aset" value="tetaplain">
                                    <input type="text" class="hidden" name="ast" value="">
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
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'#'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
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
                        <form class="form-horizontal" id="form-lain" method="post" action="{{route('aset-edit')}}">
                            <div class="form-group">
                                <label class="control-label col-md-10 pull-right">
                                    <span class="label label-default label-form pull-right">Aset Lainnya</span>
                                    <input type="text" class="hidden" name="aset" value="lain">
                                    <input type="text" class="hidden" name="ast" value="">
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
                                                            <option value="{{$rows->kode_sub_sub_kelompok.'#'.$rows->subsub_kelompok_deskripsi}}">{{$rows->kode_sub_sub_kelompok.'-'.$rows->subsub_kelompok_deskripsi}}</option>
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
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#list").change(function (e) {
                var a = $(this).val();
                if (a.substr(0, 1) == 'A') {
                    $("#edt").attr("data-target", "#modal_tanah");
                } else if (a.substr(0, 1) == 'B') {
                    $("#edt").attr("data-target", "#modal_mesin");
                } else if (a.substr(0, 1) == 'C') {
                    $("#edt").attr("data-target", "#modal_bangunan");
                } else if (a.substr(0, 1) == 'D') {
                    $("#edt").attr("data-target", "#modal_jalan");
                } else if (a.substr(0, 1) == 'E') {
                    $("#edt").attr("data-target", "#modal_tetaplain");
                } else if (a.substr(0, 1) == 'G') {
                    $("#edt").attr("data-target", "#modal_lain");
                } else {
                    $("#edt").attr("data-target", "");
                }
            });
            $("#edt").click(function (e) {
                var a = $("#list").val();
                //panel_refresh($('.modal .modal-body'), 'shown');
                $.ajax({
                    url: '{{route('aset-editdtl')}}',
                    type: 'POST',
                    data: {kode: a},
                    dataType: 'json',
                    success: function (result) {
                        $(".form-control").val('');
                        $("[name=subkel]").val(result.data['kode_bidang'] + "#" + result.data['desc_bidang']);
                        $("[name=kode_perwali]").val(result.data['kode_perwali']);
                        $("[name=no_reg]").val(result.data['no_register']);
                        $("[name=nama]").val(result.data['nama']);
                        $("[name=jumlah]").val(result.data['jumlah']);
                        $("[name=satuan]").val(result.data['satuan']);
                        $("[name=h_satuan]").val(result.data['h_satuan']);
                        $("[name=ppn]").val(result.data['ppn']);
                        $("[name=ast]").val(result.data['id_aset']);
                        $('.select').selectpicker('refresh');
                        if (a.substr(0, 1) == 'A') {
                            $("[name=luas]").val(result.dtl['luas']);
                            $("[name=tahun]").val(result.dtl['tahun']);
                            $("[name=alamat]").val(result.dtl['alamat']);
                            $("[name=status]").val(result.dtl['status']);
                            $("[name=no_sertifikat]").val(result.dtl['no_sertifikat']);
                            $("[name=tgl_sertifikat]").val(result.dtl['tgl_sertifikat']);
                            $("[name=nama_sertifikat]").val(result.dtl['nama_sertifikat']);
                            $("[name=fungsi]").val(result.dtl['fungsi']);
                            $("[name=asal]").val(result.dtl['asal']);
                            $("[name=keterangan]").val(result.dtl['keterangan']);
                        }
                        if (a.substr(0, 1) == 'B') {
                            $("[name=ruangan]").val(result.dtl['ruangan']);
                            $("[name=merk]").val(result.dtl['merk']);
                            $("[name=tipe]").val(result.dtl['tipe']);
                            $("[name=ukuran]").val(result.dtl['ukuran']);
                            $("[name=b_warna]").val(result.dtl['b_warna']);
                            $("[name=no_bpkb]").val(result.dtl['no_bpkb']);
                            $("[name=no_polisi]").val(result.dtl['no_polisi']);
                            $("[name=no_rangka]").val(result.dtl['no_rangka']);
                            $("[name=no_mesin]").val(result.dtl['no_mesin']);
                            $("[name=warna]").val(result.dtl['warna']);
                            $("[name=cc]").val(result.dtl['cc']);
                            $("[name=no_stnk]").val(result.dtl['no_stnk']);
                            $("[name=tgl_stnk]").val(result.dtl['tgl_stnk']);
                            $("[name=bbm]").val(result.dtl['bbm']);
                            $("[name=t_pengadaan]").val(result.dtl['t_pengadaan']);
                            $("[name=t_perakitan]").val(result.dtl['t_perakitan']);
                            $("[name=asal]").val(result.dtl['asal']);
                            $("[name=nama_pj]").val(result.dtl['nama_pj']);
                            $("[name=jabatan_pj]").val(result.dtl['jabatan_pj']);
                            $("[name=kondisi]").val(result.dtl['kondisi']);
                            $("[name=keterangan]").val(result.dtl['keterangan']);
                        }
                        if (a.substr(0, 1) == 'C') {
                            $("[name=tahun]").val(result.dtl['tahun']);
                            $("[name=alamat]").val(result.dtl['alamat']);
                            $("[name=tipe]").val(result.dtl['tipe']);
                            $("[name=j_bahan]").val(result.dtl['j_bahan']);
                            $("[name=j_kontruksi]").val(result.dtl['j_kontruksi']);
                            $("[name=l_lantai]").val(result.dtl['l_lantai']);
                            $("[name=l_bangunan]").val(result.dtl['l_bangunan']);
                            $("[name=jml_lantai]").val(result.dtl['jml_lantai']);
                            $("[name=fungsi]").val(result.dtl['fungsi']);
                            $("[name=asal]").val(result.dtl['asal']);
                            $("[name=no_reg_tanah]").val(result.dtl['no_reg_tanah']);
                            $("[name=luas_tanah]").val(result.dtl['l_tanah']);
                            $("[name=status_tanah]").val(result.dtl['s_tanah']);
                            $("[name=dok]").val(result.dtl['dok']);
                            $("[name=no_dok]").val(result.dtl['no_dok']);
                            $("[name=kondisi]").val(result.dtl['kondisi']);
                            $("[name=keterangan]").val(result.dtl['keterangan']);
                        }
                        if (a.substr(0, 1) == 'D') {
                            $("[name=tahun]").val(result.dtl['tahun']);
                            $("[name=alamat]").val(result.dtl['alamat']);
                            $("[name=tipe]").val(result.dtl['tipe']);
                            $("[name=j_bahan]").val(result.dtl['j_bahan']);
                            $("[name=j_kontruksi]").val(result.dtl['j_kontruksi']);
                            $("[name=panjang]").val(result.dtl['panjang']);
                            $("[name=lebar]").val(result.dtl['lebar']);
                            $("[name=luas]").val(result.dtl['luas']);
                            $("[name=fungsi]").val(result.dtl['fungsi']);
                            $("[name=asal]").val(result.dtl['asal']);
                            $("[name=no_reg_tanah]").val(result.dtl['no_reg_tanah']);
                            $("[name=luas_tanah]").val(result.dtl['l_tanah']);
                            $("[name=status_tanah]").val(result.dtl['s_tanah']);
                            $("[name=dok]").val(result.dtl['dok']);
                            $("[name=no_dok]").val(result.dtl['no_dok']);
                            $("[name=kondisi]").val(result.dtl['kondisi']);
                            $("[name=keterangan]").val(result.dtl['keterangan']);
                        }
                        if (a.substr(0, 1) == 'E') {
                            $("[name=ruangan]").val(result.dtl['ruangan']);
                            $("[name=tahun]").val(result.dtl['tahun']);
                            $("[name=judul]").val(result.dtl['judul']);
                            $("[name=pengarang]").val(result.dtl['pengarang']);
                            $("[name=pencipta]").val(result.dtl['pencipta']);
                            $("[name=daerah]").val(result.dtl['daerah']);
                            $("[name=bahan]").val(result.dtl['bahan']);
                            $("[name=jenis]").val(result.dtl['jenis']);
                            $("[name=ukuran]").val(result.dtl['ukuran']);
                            $("[name=fungsi]").val(result.dtl['fungsi']);
                            $("[name=asal]").val(result.dtl['asal']);
                            $("[name=keterangan]").val(result.dtl['keterangan']);
                        }
                        if (a.substr(0, 1) == 'G') {
                            $("[name=ruangan]").val(result.dtl['ruangan']);
                            $("[name=tahun]").val(result.dtl['tahun']);
                            $("[name=merk]").val(result.dtl['merk']);
                            $("[name=tipe]").val(result.dtl['tipe']);
                            $("[name=no_seri]").val(result.dtl['no_seri']);
                            $("[name=kondisi]").val(result.dtl['kondisi']);
                            $("[name=asal]").val(result.dtl['asal']);
                            $("[name=keterangan]").val(result.dtl['keterangan']);
                        }
                    }
                });
            });
        });
    </script>

@stop