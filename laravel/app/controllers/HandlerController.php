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
        return Datatable::query(DB::table('pkm_barang')->where('cKode','<','10000'))
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
        if (strpos($tabel, 'pkm_') !== false) {
            $data = DB::table($tabel)->where('id', sprintf("%04d", $kode))->first();
        } else {
            $data = DB::table($tabel)->where('cKode', sprintf("%04d", $kode))->first();
        }
        return Response::json(array('data' => $data));
    }

    public function get_nama_barang()
    {
        $kode = Input::get('kode');
        $tabel = Input::get('tabel');
        $data = DB::table($tabel)->where('cKode', sprintf("%06d", $kode))->first();
        if($tabel=='pkm_barang'){
            $cek = DB::table($tabel)->where('cKode', '1'.sprintf("%05d", $kode))->first();
            if(count($cek)>0){
                $n=1;
            }else{
                $n='';
            }            
        }else{
            $n='';
        }
        return Response::json(array('data' => $data,'cek'=>$n));
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
            DB::table('pkm_' . $nama)->where('cKode', sprintf("%06d", $id))->delete();
            $cek=DB::table('pkm_' . $nama)->where('cKode', '1'.sprintf("%05d", $id))->count();
            if($cek>0){
                DB::table('pkm_' . $nama)->where('cKode', '1'.sprintf("%05d", $id))->delete();
            }
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
        $t = Input::get('tipe');
        if ($t == 'hp') {
            $brg = explode('-', Input::get('kode'));
            $data = DB::table('pkm_inventori')->where('id_barang', $brg[0])->where('id_puskesmas', Auth::user()->id_puskesmas)->select(DB::raw('*,sum(stok) as tot'))->groupBy('id_barang')->first();
        } else {
            $brg = explode('-', Input::get('kode'));
            $data = DB::table('aset_data')->where('id_aset', $brg[0])->where('id_ruangan', '')->first();
        }
        return Response::json(array('brg' => $data));
    }

    public function get_pkm_alokasi()
    {
        //return Datatable::query(DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->join('pkm_inventori', 'pkm_alokasidtl.id_inventori', '=', 'pkm_inventori.id')->where('pkm_alokasi.nomor', 'like', 'ALO' . sprintf("%03d", Auth::user()->id_puskesmas) . '%')->select('pkm_alokasi.*', 'id_barang', 'nama_barang')->groupBy('pkm_alokasi.nomor'))
        return Datatable::query(DB::table('pkm_alokasi')->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('nomor', 'tanggal', 'nama_unit')
            ->addColumn('action', function ($model) {
                $nn = '';
                //$dl = '';
                $cek = DB::table('pkm_alokasidtl')->where('nomor', $model->nomor)->count();
                if ($cek > 0) {
                    $dlt = '';
                } else {
                    $dlt = '<a href="#" onclick="del(\'' . $model->nomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                }
                if ($model->data == 'mix') {
                    $nn .= '<a href="#" onclick="pop_bend(\'' . $model->nomor . '\')" title="Cetak BEND29"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;';
                }
                /*if ($model->tujuan == 'intern') {
                    $dl = '<a href="#" onclick="del(\'' . $model->nomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                }*/

                $a = explode('-', $model->nomor);
                return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="' . URL::to("sbbk/" . $model->nomor) . '" target="_blank" title="Cetak"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;' . $nn . $dlt;
            })
            ->searchColumns('pkm_alokasi.nomor', 'nama_unit')
            ->make();
    }

    public function data_alokasi()
    {
        $kode = Input::get('kode');
        $data = DB::table('pkm_alokasi')->where('nomor', $kode)->first();
        $data1 = DB::table('pkm_alokasidtl')->where('nomor', $kode)->get();
        $hasil = array();
        $n = 0;
        for ($i = 0; $i < count($data1); $i++) {
            if ($data1[$i]->tipe == 'aset') {
                $data2 = DB::table('aset_data')->where('id', $data1[$i]->id_inventori)->first();
                $hasil[$n]['tipe'] = 'aset';
                $hasil[$n]['kode'] = $data2->id . '-' . $data2->nama;
                $hasil[$n]['nama'] = $data2->id_aset . '-' . $data2->nama;
                $hasil[$n]['satuan'] = $data2->satuan;
                $hasil[$n]['jumlah'] = $data1[$i]->jumlah;
                $hasil[$n]['keterangan'] = $data1[$i]->keterangan;
            } else {
                $data3 = DB::table('pkm_inventori')->where('id', $data1[$i]->id_inventori)->first();
                $hasil[$n]['tipe'] = 'hp';
                $hasil[$n]['kode'] = $data3->id_barang . '-' . $data3->nama_barang;
                $hasil[$n]['nama'] = $data3->id_barang . '-' . $data3->nama_barang;
                $hasil[$n]['satuan'] = $data3->id_satuan . '-' . $data3->nama_satuan;
                $hasil[$n]['jumlah'] = $data1[$i]->jumlah;
                $hasil[$n]['keterangan'] = $data1[$i]->keterangan;
            }
            $n++;
        }
        return Response::json(array('data' => $data, 'dtl' => $hasil));
    }

    public function get_pkm_masuk()
    {
        return Datatable::query(DB::table('pkm_masuk')->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('nomor', 'tanggal', 'nama_supplier', 'nama_sumber')
            ->addColumn('action', function ($model) {
                $t = '';
                //cek agar bisa di delete atau tidak
                $cek = DB::table('pkm_masukdtl')->where('nomor', $model->nomor)->count();
                if ($cek > 0) {
                    $dlt = '';
                } else {
                    $dlt = '<a href="#" onclick="del(\'' . $model->nomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
                }
                //cek data baru atau tidak
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
                    $dlt;
                } else {
                    //if ($model->id_supplier != '0000') {
                    return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                    '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                    $dlt;
                    //}
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
        if ($data->barang == 'aset') {
            $data1 = DB::table('pkm_masukdtl')->join('aset_data', 'pkm_masukdtl.id_inventori', '=', 'aset_data.id')->where('nomor', $kode)->select('*', 'pkm_masukdtl.jumlah as jml')->get();
            $cek = 0;
            foreach ($data1 as $row) {
                $tmp = DB::table('pkm_alokasidtl')->where('inv_asal', $row->id_inventori)->count();
                $cek += $tmp;
            }
        } else {
            $data1 = DB::table('pkm_masukdtl')->join('pkm_inventori', 'pkm_masukdtl.id_inventori', '=', 'pkm_inventori.id')->where('nomor', $kode)->get();
            $cek = 0;
        }


        return Response::json(array('data' => $data, 'dtl' => $data1, 'cek' => $cek));
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

    //Aset
    public function aset_data()
    {
        return Datatable::query(DB::table('aset_data')->where('id_puskesmas', sprintf("%03d", Auth::user()->id_puskesmas))->where('valid', '1')->where('jumlah', '<>', '0'))
            ->showColumns('id_aset', 'nama', 'no_register', 'tahun', 'nama_ruangan', 'jumlah')
            ->addColumn('nama_ruangan', function ($model) {
                if ($model->id_ruangan == '') {
                    return 'Belum di Alokasikan';
                } else {
                    return $model->nama_ruangan;
                }
            })
            ->addColumn('tahun', function ($model) {
                $th=DB::table('aset_tanah')->select('tahun')
                ->union(DB::table('aset_mesin')->select('t_pengadaan as tahun'))
                ->union(DB::table('aset_bangunan')->select('tahun'))
                ->union(DB::table('aset_jalan')->select('tahun'))
                ->union(DB::table('aset_tetaplain')->select('tahun'))
                ->union(DB::table('aset_lain')->select('tahun'))
                ->where('id_aset',$model->id_aset)
                ->first();
                return $th->tahun;
            })
            ->searchColumns('nama', 'kode_perwali', 'nama_ruangan','id_aset')
            ->make();
    }

    public function data_aset_masuk()
    {
        return Datatable::query(DB::table('aset_masuk')->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('nomor', 'tanggal', 'nama_supplier', 'nama_sumber')
            ->addColumn('action', function ($model) {
                return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="pop_editt(\'' . $model->nomor . '\')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(\'' . $model->nomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('aset_masuk.nomor', 'nama_sumber', 'nama_supplier')
            ->make();
    }

    public function data_aset_keluar()
    {
        return Datatable::query(DB::table('aset_keluar')->where('id_puskesmas', Auth::user()->id_puskesmas))
            ->showColumns('nomor', 'tanggal', 'tujuan', 'nama_unit')
            ->addColumn('action', function ($model) {
                return '<a href="#" onclick="pop_view(\'' . $model->nomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="pop_editt(\'' . $model->nomor . '\')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="' . URL::to("bend/" . $model->nomor) . '" target="_blank" title="Cetak BEND29"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(\'' . $model->nomor . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('aset_keluar.nomor', 'nama_sumber', 'nama_supplier')
            ->make();
    }

    public function aset_detail()
    {
        $kode = explode('-', Input::get('kode'));
        $data = DB::table('kode_aset')->where('kode_sub_sub_kelompok', $kode[0])->first();
        return Response::json(array('brg' => $data));
    }

    public function get_aset_masuk()
    {
        $kode = Input::get('kode');
        $data = DB::table('aset_masuk')->where('nomor', $kode)->first();
        $data1 = DB::table('aset_masukdtl')->join('aset_data', 'aset_masukdtl.id_aset', '=', 'aset_data.id')->where('nomor', $kode)->get();
        return Response::json(array('data' => $data, 'dtl' => $data1));
    }

    public function get_aset_keluar()
    {
        $kode = Input::get('kode');
        $data = DB::table('aset_keluar')->where('nomor', $kode)->first();
        $data1 = DB::table('aset_keluardtl')->join('aset_data', 'aset_keluardtl.id_inventori', '=', 'aset_data.id')->where('nomor', $kode)->get();
        $hasil = array();
        $n = 0;
        for ($i = 0; $i < count($data1); $i++) {
            $data2 = DB::table('aset_data')->where('id', $data1[$i]->id_inventori)->first();
            $hasil[$n]['kode'] = $data2->id . '-' . $data2->nama;
            $hasil[$n]['nama'] = $data2->id_aset . '-' . $data2->nama . '-' . $data2->nama_ruangan;
            $hasil[$n]['jenis'] = $data2->tipe_aset;
            $hasil[$n]['satuan'] = $data2->satuan;
            $hasil[$n]['jumlah'] = $data1[$i]->jumlah;
            $hasil[$n]['keterangan'] = $data1[$i]->keterangan;
            $n++;
        }
        return Response::json(array('data' => $data, 'dtl' => $hasil));
    }

    public function aset_editdtl(){
        $kode = Input::get('kode');
        $data=DB::table('aset_data')->where('id_aset',$kode)->where('id_ruangan','')->first();
        $tmp=substr($kode,0,1);
        switch ($tmp) {
            case "A":
                $detail=DB::table('aset_tanah')->where('id_aset',$kode)->first();
                break;
            case "B":
                $detail=DB::table('aset_mesin')->where('id_aset',$kode)->first();
                break;
            case "C":
                $detail=DB::table('aset_bangunan')->where('id_aset',$kode)->first();
                break;
            case "D":
                $detail=DB::table('aset_jalan')->where('id_aset',$kode)->first();
                break;
            case "E":
                $detail=DB::table('aset_tetaplain')->where('id_aset',$kode)->first();
                break;
            case "G":
                $detail=DB::table('aset_lain')->where('id_aset',$kode)->first();
                break;
        }
        return Response::json(array('data' => $data, 'dtl' => $detail));
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
            $tabel = 'pkm_alokasi';
            $read = '';
        } else {
            $tabel = 'pkm_masuk';
            $read = DB::table('pkm_masuk')->where('nomor', $kode)->select('read')->get();
        }
        if($tipe == 'bend'){
            $zz=DB::table('pkm_alokasi')->where('nomor',$kode)->first();
            $data['nomor']=$kode;
            $data['pihak1']=DB::table('pkm_pegawai')->where('id_unit', $zz->id_puskesmas)->where('jabatan','2')->get();
            $data['pihak2']=DB::table('pkm_pegawai')->where('id_unit', $zz->id_unit)->where('jabatan','2')->get();
        } elseif ($tipe == 'alo') {
            $data = array();
            $ltd = DB::table($tabel . 'dtl')->where('nomor', $kode)->get();
            $n = 0;
            foreach ($ltd as $baris) {
                if ($baris->tipe == 'aset') {
                    $brg = DB::table('aset_data')->where('id', $baris->id_inventori)->first();
                    $data[$n]['id_barang'] = $brg->id_aset;
                    $data[$n]['nama_barang'] = $brg->nama;
                    $data[$n]['nama_satuan'] = $brg->satuan;
                    $data[$n]['jumlah'] = $baris->jumlah;
                    $data[$n]['harga'] = $brg->h_satuan;
                } else {
                    $brg = DB::table('pkm_inventori')->where('id', $baris->id_inventori)->first();
                    $data[$n]['id_barang'] = $brg->id_barang;
                    $data[$n]['nama_barang'] = $brg->nama_barang;
                    $data[$n]['nama_satuan'] = $brg->nama_satuan;
                    $data[$n]['jumlah'] = $baris->jumlah;
                    $data[$n]['harga'] = $brg->harga;
                }
                $n++;
            }
        } elseif ($tipe == 'am') {
            $data = array();
            $ltd = DB::table('aset_masukdtl')->where('nomor', $kode)->get();
            $n = 0;
            foreach ($ltd as $baris) {
                $brg = DB::table('aset_data')->where('id', $baris->id_aset)->first();
                $data[$n]['id_barang'] = $brg->id_aset;
                $data[$n]['nama_barang'] = $brg->nama;
                $data[$n]['nama_satuan'] = $brg->satuan;
                $data[$n]['jumlah'] = $baris->jumlah;
                $data[$n]['harga'] = $brg->h_satuan;
                $n++;
            }
        } elseif ($tipe == 'ak') {
            $data = array();
            $ltd = DB::table('aset_keluardtl')->where('nomor', $kode)->get();
            $n = 0;
            foreach ($ltd as $baris) {
                $brg = DB::table('aset_data')->where('id', $baris->id_inventori)->first();
                $data[$n]['id_barang'] = $brg->id_aset;
                $data[$n]['nama_barang'] = $brg->nama;
                $data[$n]['nama_satuan'] = $brg->satuan;
                $data[$n]['jumlah'] = $baris->jumlah;
                $data[$n]['harga'] = $brg->h_satuan;
                $n++;
            }
        } else {
            $as = DB::table($tabel)->where('nomor', $kode)->first();
            if ($as->barang == 'aset') {
                $data = DB::table($tabel . 'dtl')->join('aset_data', $tabel . 'dtl.id_inventori', '=', 'aset_data.id')
                    ->where('nomor', $kode)
                    ->select($tabel . 'dtl.jumlah', 'harga', 'id_aset as id_barang', 'nama as nama_barang', 'satuan as nama_satuan')
                    ->get();
            } else {
                $data = DB::table($tabel . 'dtl')->join('pkm_inventori', $tabel . 'dtl.id_inventori', '=', 'pkm_inventori.id')
                    ->where('nomor', $kode)
                    ->select('jumlah', $tabel . 'dtl.harga', 'id_barang', 'nama_barang', 'nama_satuan')
                    ->get();
            }
        }
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

    public function get_pegawai()
    {
        return Datatable::query(DB::table('pkm_pegawai'))
            ->showColumns('nama_pegawai', 'nip','jabatan', 'unit')
            ->addColumn('jabatan', function ($model) {
                if($model->jabatan==1){
                    return 'Kepala Puskesmas';
                }else{
                    return 'Pengurus Barang';
                }
            })
            ->addColumn('action', function ($model) {
                return '<a href="#" onclick="pop_edit(\'' . $model->id . '\')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(\'' . $model->id . '\')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('nama_pegawai', 'unit', 'nip')
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

        } else if ($nama == 'unit' || $nama == 'pegawai') {
            DB::table('pkm_' . $nama)->where('id', $id)->delete();
            return Redirect::route('dkk-' . $nama);

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
