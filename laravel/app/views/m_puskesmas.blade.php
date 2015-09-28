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
        <li><a href="#">Data Master</a></li>
        <li class="active">Puskesmas</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data Puskesmas</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <button id="tambah" class="btn btn-primary btn-rounded">
                                        Tambah Puskesmas
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <div class="btn-group">
                                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"
                                           aria-expanded="false">Export<span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{URL::to('expExcel/puskesmas')}}">Excel</a></li>
                                            <li><a target="_blank" href="{{URL::to('expPDF/puskesmas')}}">PDF</a></li>
                                        </ul>
                                    </div>
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
                    <form class="form-horizontal" method="post" action="{{route('m-puskesmas')}}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="defModalHead">Master Puskesmas</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="hidden" name="id" value="">
                                <label class="col-md-3 col-xs-12 control-label">Nama Puskesmas</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="nama" class="form-control" placeholder="Nama Puskesmas"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Alamat</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="alamat" class="form-control"
                                           placeholder="Alamat Puskesmas"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Kota</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="kota" class="form-control" placeholder="Kota Puskesmas"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Telepon</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="telp" class="form-control" placeholder="Telepon Puskesmas"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">No Fax</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="fax" class="form-control" placeholder="Fax Puskesmas"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Kepala Puskesmas</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="pimpinan" class="form-control" placeholder="Nama Kepala Puskesmas"/>
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
            $("#tambah").click(function () {
                $('[name=id]').val('');
                $('input.form-control').val('');
                $('#modal_basic').modal('show');
            });
        });
        function pop_edit(id) {
            $('[name=id]').val(id);
            $.ajax({
                url: '{{route('get-nama')}}',
                type: 'POST',
                data: {kode: id, tabel: 'tbpuskesmas'},
                dataType: 'json',
                success: function (result) {
                    $('[name=nama]').val(result.data['cNama']);
                    $('[name=alamat]').val(result.data['cAlamat']);
                    $('[name=telp]').val(result.data['cTelp']);
                    $('[name=fax]').val(result.data['cFax']);
                    $('[name=pimpinan]').val(result.data['cPimpinan']);
                }

            })
            //$('[name=nama]').val(tes);
            $('#modal_basic').modal('show');
        }
        function del(id) {
            $('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'delete/puskesmas/' + id;
            }
            //$('[name=nama]').val(tes);
        }
    </script>

@stop