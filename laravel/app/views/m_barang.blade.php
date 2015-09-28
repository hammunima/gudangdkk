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
        <li class="active">Barang</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data Barang</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <button id="tambah" class="btn btn-primary btn-rounded">
                                        Tambah Barang
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <div class="btn-group">
                                        <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"
                                           aria-expanded="false">Export<span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="{{URL::to('expExcel/barang')}}">Excel</a></li>
                                            <li><a target="_blank" href="{{URL::to('expPDF/barang')}}">PDF</a></li>
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
                    <form class="form-horizontal" method="post" action="{{route('m-barang')}}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="defModalHead">Master Barang</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="hidden" name="id" value="">
                                <label class="col-md-3 col-xs-12 control-label">Nama Barang</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="nama" class="form-control" placeholder="Nama Barang"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Jenis</label>

                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="jenis">
                                        <option value="0000-">--Pilih--</option>
                                        @foreach($m['jenis'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Merk</label>

                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="merk">
                                        <option value="0000-">--Pilih--</option>
                                        @foreach($m['merk'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Tipe</label>

                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="tipe">
                                        <option value="0000-">--Pilih--</option>
                                        @foreach($m['tipe'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Satuan</label>

                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="satuan">
                                        <option value="0000-">--Pilih--</option>
                                        @foreach($m['satuan'] as $row)
                                            <option value="{{$row->cKode.'-'.$row->cNama}}">{{$row->cNama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Kondisi</label>

                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="kondisi">
                                        <option value="0"></option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">JKN</label>

                                <div class="col-md-6 col-xs-12">
                                    <label class="switch">
                                    <input type="checkbox" name="jkn" value="1">
                                        <span></span>
                                    </label>
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
                $('[name=jkn]').attr('checked',false);
                $('input.form-control').val('');
                $('select.form-control').val('0000-');
                $('#modal_basic').modal('show');
            });
        });
        function pop_edit(id) {
            $('[name=id]').val(id);
            $.ajax({
                url: '{{route('get-nama-brg')}}',
                type: 'POST',
                data: {kode: id, tabel: 'pkm_barang'},
                dataType: 'json',
                success: function (result) {
                    var a = '0000';
                    var b = '0000';
                    var c = '0000';
                    var d = '0000';
                    if (result.data['cKdJenis'] != '') {
                        a = result.data['cKdJenis'];
                    }
                    if (result.data['cKdMerk'] != '') {
                        b = result.data['cKdMerk'];
                    }
                    if (result.data['cKdTipe'] != '') {
                        c = result.data['cKdTipe'];
                    }
                    if (result.data['cKdSatuan'] != '') {
                        d = result.data['cKdSatuan'];
                    }
                    $('[name=nama]').val(result.data['cNama']);
                    $('[name=jenis]').val(a + '-' + result.data['cJenis']);
                    $('[name=merk]').val(b + '-' + result.data['cMerk']);
                    $('[name=tipe]').val(c + '-' + result.data['cTipe']);
                    $('[name=satuan]').val(d + '-' + result.data['cSatuan']);
                    $('[name=kondisi]').val(result.data['ikondisi']);
                    if(result.cek=='1'){
                        $('[name=jkn]').attr('checked',true);    
                    }else{
                        $('[name=jkn]').attr('checked',false);
                    }
                    
                }
            })
            //$('[name=nama]').val(tes);
            $('#modal_basic').modal('show');
        }
        function del(id) {
            $('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'delete/barang/' + id;
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