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
        <li class="active">Master Pegawai</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data Pegawai Puskesmas</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <button id="tmbh" class="btn btn-primary btn-rounded">
                                        Tambah Pegawai Baru
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    &nbsp;
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
        <div class="modal" id="modal_basic" tabindex="-1" role="dialog" aria-labelledby="defModalHead"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" method="post" action="{{route('dkk-pegawai')}}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="defModalHead">Master Pegawai</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="hidden" name="id" value="">
                                <label class="col-md-3 col-xs-12 control-label">Nama Pegawai</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="nama" class="form-control" placeholder="Nama Pegawai"/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">NIP</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="nip" class="form-control" placeholder="NIP Pegawai"/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Pangkat/Golongan</label>

                                <div class="col-md-6 col-xs-12">
                                    <select name="pangkat" class="form-control select">
                                        <option value="Juru Muda/IA">Juru Muda/IA</option>
                                        <option value="Juru Muda Tk I/IB">Juru Muda Tk I/IB</option>
                                        <option value="Juru/IC">Juru/IC</option>
                                        <option value="Juru Tk I/ID">Juru Tk I/ID</option>
                                        <option value="Pengatur Muda/IIA">Pengatur Muda/IIA</option>
                                        <option value="Pengatur Muda Tk I/IIB">Pengatur Muda Tk I/IIB</option>
                                        <option value="Pengatur/IIC">Pengatur/IIC</option>
                                        <option value="Pengatur Tk I/IID">Pengatur Tk I/IID</option>
                                        <option value="Penata Muda/IIIA">Penata Muda/IIIA</option>
                                        <option value="Penata Muda Tk I/IIIB">Penata Muda Tk I/IIIB</option>
                                        <option value="Penata/IIIC">Penata/IIIC</option>
                                        <option value="Penata Tk I/IIID">Penata Tk I/IIID</option>
                                        <option value="Pembina/IVA">Pembina/IVA</option>
                                        <option value="Pembina Tk I/IVB">Pembina Tk I/IVB</option>
                                        <option value="Pembina Utama Muda/IVC">Pembina Utama Muda/IVC</option>
                                        <option value="Pembina Utama Madya/IVD">Pembina Utama Madya/IVD</option>
                                        <option value="Pembina Utama/IVE">Pembina Utama/IVE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Jabatan</label>

                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select" name="jabatan">
										<option value=""> Pilih Jabatan</option>
										<option value="1"> Kepala Puskesmas</option>
										<option value="2"> Pengurus Barang</option>
									</select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Unit Kerja</label>

                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select" name="unit">
										<option value="-"> Pilih Unit</option>
										<option value="999-Dinas Kesehatan Kota Surabaya">Dinas Kesehatan Kota Surabaya</option>
										@foreach($m['unit'] as $row)
											<option value="{{$row->id.'-'.$row->nama_puskesmas}}">{{$row->nama_puskesmas}}</option>
										@endforeach
									</select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Keterangan</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="ket" class="form-control" placeholder="Unit Kerja"/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary pull-right" value="Simpan">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--END MODAL-->
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
@stop
@section('ajax')
    {{$tabel->script()}}
    <script type="text/javascript" src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap/bootstrap-select.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#tmbh").click(function () {
                $('[name=id]').val('');
                $('[name=nama]').val('');
                $('[name=nip]').val('');
                $('[name=pangkat]').val('');
                $('[name=jabatan]').val('');
                $('[name=unit]').val('');
                $('[name=ket]').val('');
                $('#modal_basic').modal('show');
            });
        });
        function pop_edit(id) {
            $('[name=id]').val(id);
            $.ajax({
                url: '{{route('get-nama')}}',
                type: 'POST',
                data: {kode: id, tabel: 'pkm_pegawai'},
                dataType: 'json',
                success: function (result) {
                    $('[name=nama]').val(result.data['nama_pegawai']);
                    $('[name=nip]').val(result.data['nip']);
                    $('[name=pangkat]').val(result.data['pangkat']);
                    $('[name=jabatan]').val(result.data['jabatan']);
                    $('[name=unit]').val(result.data['id_unit']+'-'+toUpperCase(result.data['unit']));
                    $('[name=ket]').val(result.data['keterangan']);
                }

            })
            $('.select').selectpicker('refresh');
            //$('[name=nama]').val(tes);
            $('#modal_basic').modal('show');
        }
        function del(id) {
            $('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'deletepkm/pegawai/' + id;
            }
            //$('[name=nama]').val(tes);
        }
    </script>

@stop