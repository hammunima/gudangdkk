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
                        <ul class="panel-controls">
                            <li><a href="#" onclick="ReloadTable();" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    &nbsp;
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
                <form class="form-horizontal" id="repform" method="post" action="{{route('lap-terima')}}">
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
                                        <option value="id_puskesmas">Per Puskemas</option>
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
                            <div class="form-group jns hidden" id="p_pkm">
                                <label class="col-md-2 control-label">Puskesmas</label>

                                <div class="col-md-5">
                                    <select class="form-control select" data-live-search="true" name="p_pkm">
                                        <option value="">Semua Puskesmas</option>
                                        @foreach($m['pkm'] as $row)
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
            $('[name=jns]').change(function () {
                if ($(this).val() == 'id_supplier') {
                    $('.jns').addClass('hidden');
                    $('#p_sup').removeClass('hidden');
                } else if ($(this).val() == 'id_sumber') {
                    $('.jns').addClass('hidden');
                    $('#p_sumber').removeClass('hidden');
                } else if ($(this).val() == 'id_puskesmas') {
                    $('.jns').addClass('hidden');
                    $('#p_pkm').removeClass('hidden');
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
                    url: '{{route('get-brg-pkm')}}',
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
            var kode = 'MSK{{sprintf("%03d", Auth::user()->id_puskesmas)}}-' + id;
            $('select.form-control').val('0000-');
            $('input.form-control').val('');
            $('#body-table').empty();
            $.ajax({
                url: '{{route('get-msk')}}',
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
                    $('[name=tgl]').val(result.data['tanggal']);
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
                        '<td><input type="text" class="hidden" readonly name="barang[]" value="' + result.dtl[i]['id_barang'] + '-' + result.dtl[i]['nama_barang'] + '"><label class="control-label">' + result.dtl[i]['id_barang'] + '-' + result.dtl[i]['nama_barang'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="jumlah[]" value="' + result.dtl[i]['jumlah'] + '"><label class="control-label pull-right">' + result.dtl[i]['jumlah'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="satuan[]" value="' + result.dtl[i]['id_satuan'] + '-' + result.dtl[i]['nama_Satuan'] + '"><label class="control-label">' + result.dtl[i]['id_satuan'] + '-' + result.dtl[i]['nama_satuan'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="h_satuan[]" value="' + result.dtl[i]['harga'] + '"><label class="control-label pull-right">' + result.dtl[i]['harga'] + '</label></td>' +
                        '<td><input type="text" class="hidden" readonly name="total[]" value="' + (result.dtl[i]['harga'] * result.dtl[i]['jumlah']) + '"><label class="control-label pull-right">' + (result.dtl[i]['harga'] * result.dtl[i]['jumlah']) + '</label></td>' +
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
                url: '{{route('dkk-get-detail')}}',
                type: 'POST',
                data: {kode: id, tipe: 'msk'},
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
        function wrong(id) {
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
                                data: {kode: id, tipe: 'wrong'},
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
                window.location.href = 'deletepkm/barang/' + id;
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