<?php

class HandlerController extends BaseController
{

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function index()
    {
        return View::make('dash');
    }

    public function get_data_trm()
    {
        $kode = Input::get('kode');
        $data = DB::table('tbterima')->where('cNomor', $kode)->first();
        $data1 = DB::table('tbterimadtl')->where('cNomor', $kode)->get();
        return Response::json(array('data' => $data, 'dtl' => $data1));
    }

    public function get_data_klr()
    {
        $kode = Input::get('kode');
        $data = DB::table('tbkeluar')->where('cNomor', $kode)->first();
        $data1 = DB::table('tbkeluardtl')->where('cNomor', $kode)->get();
        return Response::json(array('data' => $data, 'dtl' => $data1));
    }

    public function each_barang()
    {
        $kode = explode('-', Input::get('kode'));
        $data = DB::table('tbbarang')->where('cKode', $kode[0])->first();
        return Response::json(array('brg' => $data));
    }

    public function each_klr()
    {
        $kode = explode('-', Input::get('kode'));
        $stok = DB::table('tbbarang')->join('tbstock', 'tbbarang.cKode', '=', 'tbstock.cKode')->groupBy('tbstock.cKode')->select(DB::raw('sum(nQtyReal) as sum'))->where('tbstock.cKode', $kode[0])->first();
        $data = DB::table('tbbarang')->where('cKode', $kode[0])->first();
        //$stok=DB::table('tbinventori')->where('id',$kode[1])->first();
        return Response::json(array('brg' => $data, 'stok' => $stok));
    }

    public function get_adj_barang()
    {
        return Datatable::query(DB::table('tbadj'))
            ->showColumns('cNomor', 'dTanggal', 'cKeterangan')
            ->addColumn('action', function ($model) {
                $a = explode('-', $model->cNomor);
                if ($model->lPosted == '0') {
                    $t = '&nbsp&nbsp;<label class="label-danger">&nbsp;</label>';
                } else {
                    $t = '';
                }
                return '<a href="#" onclick="del(' . $a[1] . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(\'' . $model->cNomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>' . $t;
            })
            ->searchColumns('cNomor', 'dTanggal', 'cKeterangan')
            ->make();
    }

    public function get_trm_barang()
    {
        //return Datatable::query(DB::table('tbterima')->join('tbterimadtl', 'tbterima.cNomor', '=', 'tbterimadtl.cNomor')->groupBy('tbterima.cNomor')->select('tbterima.*', 'cKode', 'cNama'))
        return Datatable::query(DB::table('tbterima'))
            ->showColumns('cNomor', 'dTanggal', 'cSupplier', 'cSumber', 'cBidang')
            ->addColumn('action', function ($model) {
                $a = explode('-', $model->cNomor);
                if ($model->lPosted == '0') {
                    $t = '&nbsp&nbsp;<label class="label-danger">&nbsp;</label>';
                } else {
                    $t = '';
                }
                return '<a href="#" onclick="pop_view(\'' . $model->cNomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(\'' . $model->cNomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>' . $t;
            })
            ->searchColumns('tbterima.cNomor', 'cSumber', 'cBidang', 'cSupplier')
            ->make();
    }

    public function get_klr_barang()
    {
        //return Datatable::query(DB::table('tbkeluar')->join('tbkeluardtl', 'tbkeluar.cNomor', '=', 'tbkeluardtl.cNomor')->groupBy('tbkeluar.cNomor')->select('tbkeluar.*', 'cKode', 'cNama'))
        return Datatable::query(DB::table('tbkeluar'))
            ->showColumns('cNomor', 'dTanggal', 'cBidang', 'cPuskesmas')
            ->addColumn('action', function ($model) {
                $a = explode('-', $model->cNomor);
                if ($model->lPosted == '0') {
                    $t = '&nbsp&nbsp;<label class="label-danger">&nbsp;</label>';
                } else {
                    $t = '';
                }
                return '<a href="#" onclick="pop_view(\'' . $model->cNomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="' . URL::to("nota/" . $model->cNomor) . '" target="_blank" title="Cetak"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(\'' . $model->cNomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>' . $t;
            })
            ->searchColumns('tbkeluar.cNomor', 'cBidang', 'cPuskesmas')
            ->make();
    }

    public function get_stok_barang()
    {
        //return Datatable::query(DB::table('tbbarang')->join('tbstock', 'tbbarang.id', '=', 'tbstock.cKode')->groupBy('cKode')->select(DB::raw('tbbarang.*, tbstock.cNama as cnama, sum(nQtyReal) as sum')))
        //$data = DB::table('tbstock')->groupBy('cKode')->select(DB::raw('cKode, sum(nQtyReal) as sum'))->get();
        return Datatable::query(DB::table('tbinventori')->where('urutan', '<>', ''))
            //->showColumns('cnama', 'satuan', 'harga', 'sum')
            ->showColumns('cKode', 'cNama', 'cSupplier', 'dTanggal', 'stok')
            ->searchColumns('cKode', 'cNama')
            //->searchColumns('nama', 'jenis')
            ->make();
    }

    public function get_data_barang()
    {
        return Datatable::query(DB::table('tbbarang'))
            ->showColumns('cKode', 'cNama', 'cJenis', 'cSatuan')
            ->addColumn('action', function ($model) {
                return '<a href="#" onclick="pop_edit(' . (int)$model->cKode . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(' . (int)$model->cKode . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('cKode', 'cNama', 'cJenis')
            ->make();
    }

    public function get_tabel($nama)
    {
        if ($nama == 'supplier') {
            return Datatable::query(DB::table('tb' . $nama))
                ->showColumns('cKode', 'cNama', 'cAlamat')
                ->addColumn('action', function ($model) {
                    return '<a href="#" onclick="pop_edit(' . (int)$model->cKode . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                    '<a href="#" onclick="del(' . (int)$model->cKode . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                })
                ->searchColumns('cKode', 'cNama')
                ->make();
        } elseif ($nama == 'sumberanggaran') {
            return Datatable::query(DB::table('tb' . $nama))
                ->showColumns('cKode', 'cNama', 'cAsal')
                ->addColumn('action', function ($model) {
                    return '<a href="#" onclick="pop_edit(' . (int)$model->cKode . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                    '<a href="#" onclick="del(' . (int)$model->cKode . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                })
                ->searchColumns('cKode', 'cNama')
                ->make();
        } else {
            return Datatable::query(DB::table('tb' . $nama))
                ->showColumns('cKode', 'cNama')
                ->addColumn('action', function ($model) {
                    return '<a href="#" onclick="pop_edit(' . (int)$model->cKode . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                    '<a href="#" onclick="del(' . (int)$model->cKode . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                })
                ->searchColumns('cKode', 'cNama')
                ->make();
        }
    }

    public function get_nama()
    {
        $kode = Input::get('kode');
        $tabel = Input::get('tabel');
        $data = DB::table($tabel)->where('cKode', sprintf("%04d", $kode))->first();
        return Response::json(array('data' => $data));
    }

    public function get_nama_barang()
    {
        $kode = Input::get('kode');
        $tabel = Input::get('tabel');
        $data = DB::table($tabel)->where('cKode', sprintf("%06d", $kode))->first();
        return Response::json(array('data' => $data));
    }

    public function get_detail()
    {
        $kode = Input::get('kode');
        $tipe = Input::get('tipe');
        if ($tipe == 'keluar') {
            $data = DB::table('tbkeluardtl')->join('tbinventori', 'tbkeluardtl.inventori', '=', 'tbinventori.id')->where('cNomor', $kode)->select('tbkeluardtl.*', 'harga')->get();
            if (count($data) == 0) {
                $data = DB::table('tbkeluardtl')->join('tbinventori', 'tbkeluardtl.cKode', '=', 'tbinventori.cKode')->where('cNomor', $kode)->where('urutan', 1)->select('tbkeluardtl.*', 'harga')->get();
            }
        } else {
            $data = DB::table('tb' . $tipe . 'dtl')->where('cNomor', $kode)->get();
        }
        return Response::json(array('data' => $data));
    }

    public function delete($nama, $id)
    {
        if ($nama == 'barang') {
            DB::table('tb' . $nama)->where('cKode', sprintf("%06d", $id))->delete();
            return Redirect::route('m-' . $nama);
        } else {
            if ($nama == 'tbkeluar') {
                DB::table($nama)->where('cNomor', $id)->delete();
                $data = DB::table($nama . 'dtl')->where('cNomor', $id)->get();
                foreach ($data as $row) {
                    $inventori = DB::table('tbinventori')->where('cKode', $row->cKode)->where('urutan', 1)->first();
                    DB::table('tbinventori')->where('id', $inventori->id)->update(array('stok' => ($inventori->stok + $row->nQty)));
                }
                DB::table($nama . 'dtl')->where('cNomor', $id)->delete();
                DB::table('tbstock')->where('cNomor', $id)->delete();
                return Redirect::route('m-pengeluaran');
            } else {
                DB::table($nama)->where('cNomor', $id)->delete();
                $data = DB::table($nama . 'dtl')->where('cNomor', $id)->get();
                foreach ($data as $row) {
                    $inventori = DB::table('tbinventori')->where('cKode', $row->cKode)->where('urutan', '<>', '')->first();
                    DB::table('tbinventori')->where('id', $inventori->id)->update(array('stok' => ($inventori->stok - $row->nQty)));
                }
                DB::table($nama . 'dtl')->where('cNomor', $id)->delete();
                DB::table('tbstock')->where('cNomor', $id)->delete();
                if ($nama == 'tbterima') {
                    return Redirect::route('m-penerimaan');
                } else {
                    return Redirect::route('m-penyesuaian');
                }

            }

        }
    }

    //Aplikasi Puskesmas
    //Puskesmas
    public function get_stok_pkm()
    {
        return Datatable::query(DB::table('pkm_inventori')->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('id_barang', 'nama_barang', 'nama_supplier', 'nama_sumber', 'tanggal', 'stok')
            ->searchColumns('id_barang', 'nama_barang', 'nama_supplier')
            ->make();
    }

    public function stok_alokasi()
    {
        $brg = explode('-', Input::get('kode'));
        $data = DB::table('pkm_inventori')->where('id_barang', $brg[0])->where('id_puskesmas', Auth::user()->id_puskesmas)->select(DB::raw('*,sum(stok) as tot'))->groupBy('id_barang')->first();
        return Response::json(array('brg' => $data));
    }

    public function get_pkm_alokasi()
    {
        //return Datatable::query(DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')->where('pkm_alokasi.nomor', 'like', 'ALO' . sprintf("%03d", Auth::user()->id_puskesmas) . '%')->select('pkm_alokasi.*', 'id_barang', 'nama_barang')->groupBy('pkm_alokasi.nomor'))
        return Datatable::query(DB::table('pkm_alokasi')->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('nomor', 'tanggal', 'nama_unit')
            ->addColumn('action', function ($model) {
                $a = explode('-', $model->nomor);
                return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="' . URL::to("cetakpkm/" . $model->nomor) . '" target="_blank" title="Cetak"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(\'' . $model->nomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('pkm_alokasi.nomor', 'nama_unit')
            ->make();
    }

    public function data_alokasi()
    {
        $kode = Input::get('kode');
        $data = DB::table('pkm_alokasi')->where('nomor', $kode)->first();
        $data1 = DB::table('pkm_alokasidtl')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')->where('nomor', $kode)->get();
        return Response::json(array('data' => $data, 'dtl' => $data1));
    }

    public function get_pkm_masuk()
    {
        return Datatable::query(DB::table('pkm_masuk')->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('nomor', 'tanggal', 'nama_supplier', 'nama_sumber')
            ->addColumn('action', function ($model) {
                $t = '';
                if ($model->read == '1') {
                    $t .= '&nbsp;&nbsp;<span class="label label-warning">Baru</span>';
                }
                $a = explode('-', $model->nomor);
                if ($model->status == '1') {
                    return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                    '<span class="label label-danger">Terkunci</span>' . $t;
                } elseif ($model->status == 'wrong') {
                    return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                    '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;<span class="label label-warning">Tidak Sesuai</span>&nbsp;&nbsp;' .
                    '<a href="#" onclick="del(\'' . $model->nomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                } else {
                    if ($model->id_supplier != '0000') {
                        return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                        '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                        '<a href="#" onclick="del(\'' . $model->nomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                    }
                }
            })
            ->searchColumns('pkm_masuk.nomor', 'nama_sumber', 'nama_supplier')
            ->make();
    }

    public function each_barang_pkm()
    {
        $kode = explode('-', Input::get('kode'));
        $data = DB::table('pkm_barang')->where('cKode', $kode[0])->first();
        return Response::json(array('brg' => $data));
    }

    public function data_masuk()
    {
        $kode = Input::get('kode');
        $data = DB::table('pkm_masuk')->where('nomor', $kode)->first();
        $data1 = DB::table('pkm_masukdtl')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')->where('nomor', $kode)->get();
        return Response::json(array('data' => $data, 'dtl' => $data1));
    }

    public function get_tabel_pkm($nama)
    {
        return Datatable::query(DB::table('pkm_' . $nama)->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('kode', 'nama_supplier', 'alamat_supplier')
            ->addColumn('action', function ($model) {
                return '<a href="#" onclick="pop_edit(' . (int)$model->id . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(' . (int)$model->id . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('kode', 'nama_supplier')
            ->make();
    }

    public function tabel_user()
    {
        return Datatable::query(DB::table('pkm_login')->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('role', 'nama', 'username')
            ->addColumn('action', function ($model) {
                if ($model->role == '1') {
                    return '<a href="#" onclick="pop_edit(' . $model->id . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;';
                } else {
                    return '<a href="#" onclick="pop_edit(' . $model->id . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                    '<a href="#" onclick="del(' . $model->id . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                }

            })
            ->searchColumns('nama', 'username')
            ->make();
    }

    public function get_nama_pkm()
    {
        $kode = Input::get('kode');
        $tabel = Input::get('tabel');
        $data = DB::table($tabel)->where('id', $kode)->first();
        return Response::json(array('data' => $data));
    }

    public function pkm_cek()
    {
        $a = DB::table('pkm_masuk')->where('read', '1')->where('id_puskesmas', Auth::user()->id_puskesmas)->count();
        return Response::json(array('cek' => $a));
    }

    public function cek_uname()
    {
        $a = DB::table('pkm_login')->where('username', Input::get('kode'))->count();
        return Response::json(array('cek' => $a));
    }

    //Admin DKK
    public function dkk_get_stok()
    {
        return Datatable::query(DB::table('pkm_inventori'))
            ->showColumns('id_barang', 'nama_barang', 'nama_supplier', 'nama_puskesmas', 'stok')
            ->searchColumns('id_barang', 'nama_barang', 'nama_supplier', 'nama_puskesmas')
            ->make();
    }

    public function dkk_get_alokasi()
    {
        return Datatable::query(DB::table('pkm_alokasi'))
            ->showColumns('nomor', 'tanggal', 'nama_puskesmas', 'nama_unit')
            ->addColumn('action', function ($model) {
                return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;';
                //'<a href="' . URL::to("nota/" . $model->nomor) . '" target="_blank" title="Cetak"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;' .
                //'<a href="' . URL::to("save/" . $model->nomor) . '" target="_blank" title="Unduh"><i class="glyphicon glyphicon-save"></i></a>&nbsp;&nbsp;';
            })
            ->searchColumns('pkm_alokasi.nomor', 'pkm_alokasi.nama_puskesmas', 'nama_unit')
            ->make();
    }

    public function dkk_get_masuk()
    {
        //->join('pkm_masukdtl', 'pkm_masuk.nomor', '=', 'pkm_masukdtl.nomor')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')->select('pkm_masuk.*', 'id_barang', 'nama_barang')->groupBy('pkm_masuk.nomor'))
        return Datatable::query(DB::table('pkm_masuk'))
            ->showColumns('nomor', 'tanggal', 'nama_puskesmas', 'nama_supplier', 'nama_sumber')
            ->addColumn('action', function ($model) {
                if ($model->status == '1') {
                    return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;&nbsp;' .
                    '<span class="label label-danger">Terkunci</span>&nbsp;&nbsp;&nbsp;<a href="#" onclick="unlock(\'' . $model->nomor . '\')" title="Buka Kunci"><i class="fa fa-unlock-alt"></i></a>&nbsp;&nbsp;';
                } elseif ($model->status == 'wrong') {
                    return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                    '&nbsp;&nbsp;<span class="label label-warning">Tidak Sesuai</span>';
                } else {
                    return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                    '&nbsp;&nbsp;<a href="#" onclick="lock(\'' . $model->nomor . '\')" title="Kunci"><i class="fa fa-lock"></i></a>&nbsp;&nbsp;' .
                    '&nbsp;&nbsp;<a href="#" onclick="wrong(\'' . $model->nomor . '\')" title="Salah"><i class="fa fa-warning"></i></a>';
                }
            })
            ->searchColumns('nomor', 'nama_sumber', 'nama_supplier', 'nama_puskesmas')
            ->make();
    }

    public function dkk_get_detail()
    {
        $kode = Input::get('kode');
        $tipe = Input::get('tipe');
        if ($tipe == 'alo') {
            $tabel = 'pkm_alokasidtl';
            $read = '';
        } else {
            $tabel = 'pkm_masukdtl';
            $read = DB::table('pkm_masuk')->where('nomor', $kode)->select('read')->get();
        }
        $data = DB::table($tabel)->join('pkm_inventori', $tabel . '.id_inventori', '=', 'pkm_inventori.id')
            ->where('nomor', $kode)
            ->select('jumlah', $tabel . '.harga', 'id_barang', 'nama_barang', 'nama_satuan')
            ->get();
        return Response::json(array('data' => $data, 'read' => $read));
    }

    public function dkk_validate()
    {
        $kode = Input::get('kode');
        $tipe = Input::get('tipe');
        if ($tipe == 'lock') {
            DB::table('pkm_masuk')->where('nomor', $kode)->update(array('status' => 1));
            $pkm = DB::table('pkm_masuk')->where('nomor', $kode)->first();
            $data = DB::table('pkm_masukdtl')->where('nomor', $kode)->get();
            for ($i = 0; $i < count($data); $i++) {
                $invent = DB::table('pkm_inventori')->where('id', $data[$i]->id_inventori)->first();
                DB::table('pkm_inventori')->where('id', $invent->id)->update(array('stok' => $invent->stok + $data[$i]->jumlah));
                $upd = DB::table('pkm_inventori')->where('id_barang', $invent->id_barang)->where('id_puskesmas', $pkm->id_puskesmas)->orderBy('tanggal', 'asc')->get();
                $n = 1;
                for ($j = 0; $j < count($upd); $j++) {
                    if (count($upd) == 1) {
                        DB::table('pkm_inventori')->where('id', $upd[$j]->id)->update(array('urutan' => 1));
                    } else {
                        if ($upd[$j]->stok == 0) {
                            DB::table('pkm_inventori')->where('id', $upd[$j]->id)->update(array('urutan' => ''));
                        } else {
                            DB::table('pkm_inventori')->where('id', $upd[$j]->id)->update(array('urutan' => $n));
                            $n++;
                        }
                    }
                }
            }
        } else if ($tipe == 'unlock') {
            DB::table('pkm_masuk')->where('nomor', $kode)->update(array('status' => 0));
            $data = DB::table('pkm_masukdtl')->where('nomor', $kode)->get();
            for ($i = 0; $i < count($data); $i++) {
                $invent = DB::table('pkm_inventori')->where('id', $data[$i]->id_inventori)->first();
                DB::table('pkm_inventori')->where('id', $invent->id)->update(array('stok' => $invent->stok - $data[$i]->jumlah));
            }
        } else if ($tipe == 'read') {
            DB::table('pkm_masuk')->where('nomor', $kode)->update(array('read' => 0));
        } else {
            DB::table('pkm_masuk')->where('nomor', $kode)->update(array('status' => 'wrong'));
        }
        return Response::json(array('data' => 'sukses'));
    }

    public function dkk_cek()
    {
        $a = DB::table('pkm_masuk')->where('status', '0')->count();
        return Response::json(array('cek' => $a));
    }

    public function get_unit()
    {
        return Datatable::query(DB::table('pkm_unit'))
            ->showColumns('id', 'nama_unit')
            ->addColumn('action', function ($model) {
                return '<a href="#" onclick="pop_edit(\'' . $model->id . '\')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(\'' . $model->id . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('id', 'nama_unit')
            ->make();
    }

    //General
    public function delete_pkm($nama, $id)
    {
        if ($nama == 'barang') {
            DB::table('pkm_' . $nama)->where('cKode', sprintf("%06d", $id))->delete();
            return Redirect::route('dkk-' . $nama);
        } elseif ($nama == 'pkm_alokasi') {
            DB::table($nama)->where('nomor', $id)->delete();
            $data = DB::table($nama . 'dtl')->where('nomor', $id)->get();
            foreach ($data as $row) {
                $inventori = DB::table('pkm_inventori')->where('id', $row->id_inventori)->first();
                DB::table('pkm_inventori')->where('id', $row->id_inventori)->update(array('stok' => ($inventori->stok + $row->jumlah)));
            }
            DB::table($nama . 'dtl')->where('nomor', $id)->delete();
            //DB::table('tbstock')->where('cNomor', $id)->delete();
            return Redirect::route('pkm-alokasi');

        } elseif ($nama == 'pkm_masuk') {
            $msk = DB::table($nama)->where('nomor', $id)->first();
            if ($msk->status == '1') {
                $data = DB::table($nama . 'dtl')->where('nomor', $id)->get();
                foreach ($data as $row) {
                    $inventori = DB::table('pkm_inventori')->where('id', $row->id_inventori)->first();
                    DB::table('pkm_inventori')->where('id', $row->id_inventori)->update(array('stok' => ($inventori->stok - $row->jumlah)));
                }
            }
            DB::table($nama . 'dtl')->where('nomor', $id)->delete();
            DB::table($nama)->where('nomor', $id)->delete();
            return Redirect::route('pkm-masuk');

        } else {
            DB::table('pkm_' . $nama)->where('id', $id)->delete();
            if ($nama == 'login') {
                return Redirect::route('pkm-user');
            } else {
                return Redirect::route('pkm-' . $nama);
            }
        }
    }

    public function get_barang_pkm()
    {
        $kode = Input::get('kode');
        $data = DB::table('pkm_inventori')->where('jenis', $kode)->select(DB::raw('distinct(id_barang),nama_barang'))->get();
        return Response::json(array('data' => $data));
    }

}
