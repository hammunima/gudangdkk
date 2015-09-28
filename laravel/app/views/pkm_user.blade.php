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
        <li class="active">User Login</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <!-- START DEFAULT DATATABLE -->
                <div class="panel panel-default" id="tabel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Data User Login</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <button id="tambah" class="btn btn-primary btn-rounded">
                                        Tambah User
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-1">

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
                    <form class="form-horizontal" method="post" action="{{route('pkm-user')}}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span
                                        class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="defModalHead">Master User</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="hidden" name="id" value="">
                                <label class="col-md-3 col-xs-12 control-label">Nama</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="nama" class="form-control" placeholder="Nama Pegawai"/>
                                </div>

                            </div>
                            <div class="form-group" id="div_uname">
                                <label class="col-md-3 col-xs-12 control-label">Username</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="uname" class="form-control" placeholder="Username"/>
                                </div>
                                <label class="control-label" id="warn"></label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Password</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="password" name="pass" class="form-control" placeholder="Password"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Ulangi Password</label>

                                <div class="col-md-6 col-xs-12">
                                    <input type="password" name="pass2" class="form-control" placeholder="Ulangi Password"/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" id="submit" class="btn btn-primary pull-right" value="Simpan">
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
            $("#submit").click(function () {
                if ($("[name=nama]").val() == '') {
                    alert('Nama Harus Diisi');
                    $("[name=nama]").focus();
                    return false;
                }
                if ($("[name=uname]").val() == '' || $("#div_uname").hasClass('has-error') == true) {
                    alert('Username tidak sesuai');
                    $("[name=uname]").focus();
                    return false;
                }
                if ($("[name=pass]").val() == '' || $("[name=pass]").val() != $("[name=pass2]").val()) {
                    alert('Password tidak sesuai');
                    $("[name=pass]").focus();
                    return false;
                }
            });
            $("[name=uname]").keyup(function () {
                var nama = $(this).val();
                if (nama.length < 4) {
                    $("#div_uname").removeClass('has-success');
                    $("#div_uname").addClass('has-error');
                    $("#warn").html('Min 4 karakter');
                } else {
                    $("#div_uname").removeClass('has-error');
                    $.ajax({
                        url: '{{route('cek-uname')}}',
                        type: 'POST',
                        data: {kode: nama},
                        dataType: 'json',
                        success: function (result) {
                            if (result.cek > 0) {
                                $("#div_uname").removeClass('has-success');
                                $("#div_uname").addClass('has-error');
                                $("#warn").html('Sudah digunakan');
                            } else {
                                $("#div_uname").removeClass('has-error');
                                $("#div_uname").addClass('has-success');
                                $("#warn").html('Tersedia');
                            }
                        }
                    })
                }
            });
        });
        function pop_edit(id) {
            $('[name=id]').val(id);
            $.ajax({
                url: '{{route('get-nama-pkm')}}',
                type: 'POST',
                data: {kode: id, tabel: 'pkm_login'},
                dataType: 'json',
                success: function (result) {
                    $('[name=nama]').val(result.data['nama']);
                    $('[name=uname]').val(result.data['username']);
                    $('[name=pass]').val(result.data['password']);
                    $('[name=pass2]').val(result.data['password']);
                }

            })
            //$('[name=nama]').val(tes);
            $('#modal_basic').modal('show');
        }
        function del(id) {
            $('[name=id]').val(id);
            var r = confirm("Yakin ingin menghapus?");
            if (r == true) {
                window.location.href = 'deletepkm/login/' + id;
            }
            //$('[name=nama]').val(tes);
        }
    </script>

@stop