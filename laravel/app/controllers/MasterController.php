<?php

class MasterController extends BaseController
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
        /*$a = DB::table('tbstock')->groupBy('cKode')->get();
        foreach ($a as $row) {
            $b = DB::table('tbinventori')->where('cKode', $row->cKode)->count();
            if ($b == 0) {
                echo $row->cKode . '<br>';
            }
        }*/
        //generate tabel inventori
        $masuk = DB::table('tbterima')
            ->join('tbterimadtl', 'tbterima.cNomor', '=', 'tbterimadtl.cNomor')
            ->groupBy('cKode', 'cKdSupplier')
            ->select(DB::raw('cKode,cNama,cKdSupplier,cSupplier,dTanggal,nHarga, sum(nQty) as stok'))
            ->orderBy('cKode', 'asc')
            ->orderBy('tbterimadtl.cNomor', 'desc')
            ->get();
        $brg = '';
        foreach ($masuk as $row) {
            $db = DB::table('tbinventori')->where('cKode', $row->cKode)->where('cKdSupplier', $row->cKdSupplier)->count();
            if ($brg != $row->cKode) {
                $n = 1;
                $brg = $row->cKode;
            }
            if ($db == 0) {
                DB::table('tbinventori')->insert(
                    array(
                        'cKode' => $row->cKode,
                        'cNama' => $row->cNama,
                        'cKdSupplier' => $row->cKdSupplier,
                        'cSupplier' => $row->cSupplier,
                        'stok' => $row->stok,
                        'harga' => $row->nHarga,
                        'dTanggal' => $row->dTanggal,
                        'urutan' => $n
                    )
                );
                $n++;
            }

        }
        $adj = DB::table('tbadj')
            ->join('tbadjdtl', 'tbadj.cNomor', '=', 'tbadjdtl.cNomor')
            ->groupBy('cKode')
            ->select(DB::raw('cKode,cNama,dTanggal, sum(nQty) as stok'))
            ->orderBy('cKode', 'asc')
            ->orderBy('tbadjdtl.cNomor', 'desc')
            ->get();
        foreach ($adj as $row) {
            $n = DB::table('tbinventori')->where('cKode', $row->cKode)->count();
            if ($n == 0) {
                DB::table('tbinventori')->insert(
                    array(
                        'cKode' => $row->cKode,
                        'cNama' => $row->cNama,
                        'cKdSupplier' => '',
                        'cSupplier' => '',
                        'stok' => $row->stok,
                        'harga' => 0,
                        'dTanggal' => $row->dTanggal,
                        'urutan' => 1
                    )
                );
            }
        }
    }

    public function index2()
    {
        set_time_limit(300);
        $stok = DB::table('tbstock')->groupBy('cKode')->select(DB::raw('cKode, sum(nQtyReal) as sum'))->orderBy('cKode', 'asc')->get();
        foreach ($stok as $row) {
            $data = DB::table('tbinventori')->where('cKode', $row->cKode)->orderBy('urutan', 'asc')->get();
            $n = 1;
            $i = 0;
            $jum = 0;
            $cek = false;
            foreach ($data as $baris) {
                if ($cek == false) {
                    $jum += $baris->stok;
                    if ($row->sum <= $jum) {
                        $cek = true;
                        if ($i == 0) {
                            DB::table('tbinventori')->where('cKode', $baris->cKode)->where('urutan', $baris->urutan)
                                ->update(array('stok' => (int)$row->sum));
                        } else {
                            $st = $jum - $row->sum;
                            DB::table('tbinventori')->where('cKode', $baris->cKode)->where('urutan', $baris->urutan)
                                ->update(array('stok' => $baris->stok - $st));
                        }

                    }
                } else {
                    DB::table('tbinventori')->where('cKode', $baris->cKode)->where('urutan', $baris->urutan)
                        ->update(array('stok' => 0, 'urutan' => ''));
                }
                $i++;
            }
            $data = DB::table('tbinventori')->where('cKode', $row->cKode)->where('urutan', '<>', '')->orderBy('urutan', 'desc')->get();
            foreach ($data as $baris) {
                DB::table('tbinventori')->where('id', $baris->id)->update(array('urutan' => $n));
                $n++;
            }
        }

    }

    public function to_pkm()
    {
        set_time_limit(600);
        $data = DB::table('tbkeluar')->where('dTanggal', '>=', '2015-01-01')->where('cKdPuskesmas', '>=', '0001')->where('cKdPuskesmas', '<=', '0062')->get();
        //$data1 = DB::table('tbkeluar')->where('dTanggal', '>=', '2015-01-01')->where('cKdPuskesmas', '0001')->get();
        foreach ($data as $row) {
            $maks = DB::table('pkm_masuk')->where('nomor', 'like', 'MSK' . sprintf("%03d", $row->cKdPuskesmas) . '-' . date('ym') . '%')->max('nomor');
            if ($maks == '') {
                $nmr = 'MSK' . sprintf("%03d", $row->cKdPuskesmas) . '-' . date('ym') . sprintf("%04d", 1);
            } else {
                $nmr = substr($maks, 0, 11) . sprintf("%04d", substr($maks, 11) + 1);
            }
            DB::table('pkm_masuk')->insert(
                array(
                    'nomor' => $nmr,
                    'tanggal' => $row->dTanggal,
                    'id_puskesmas' => $row->cKdPuskesmas,
                    'nama_puskesmas' => $row->cPuskesmas,
                    'id_supplier' => sprintf("%04d", 9999),
                    'nama_Supplier' => 'DKK',
                    'id_sumber' => sprintf("%04d", 1),
                    'nama_sumber' => 'APBD',
                    'tahun' => date('Y'),
                    'keterangan' => $row->cKeterangan,
                    'status' => 1
                )
            );
            $brg = DB::table('tbkeluardtl')->where('cNomor', $row->cNomor)->get();
            foreach ($brg as $baris) {
                if (strpos($baris->cNama, 'BANTUAN') !== false) {
                    DB::table('pkm_masuk')->where('nomor', $nmr)->update(array('id_sumber' => sprintf("%04d", 2), 'nama_sumber' => 'APBN'));
                    $smbr = 'APBN';
                } else {
                    $smbr = 'APBD';
                }
                $data = DB::table('pkm_inventori')->where('id_barang', $baris->cKode)->where('id_puskesmas', $row->cKdPuskesmas)->where('id_supplier', '9999')->first();
                $inv = DB::table('tbinventori')->where('cKode', $baris->cKode)->where('urutan', 1)->select('harga')->first();
                if (count($inv) == 0) {
                    $hrg = 0;
                } else {
                    $hrg = $inv->harga;
                }
                if (count($data) == 0) {
                    $jns = DB::table('tbbarang')->where('cKode', $baris->cKode)->select('cJenis')->first();
                    $id = DB::table('pkm_inventori')->insertGetId(
                        array(
                            'id_barang' => $baris->cKode,
                            'nama_barang' => $baris->cNama,
                            'id_satuan' => $baris->cKdSatuan,
                            'nama_satuan' => $baris->cSatuan,
                            'id_puskesmas' => $row->cKdPuskesmas,
                            'nama_puskesmas' => $row->cPuskesmas,
                            'id_supplier' => sprintf("%04d", 9999),
                            'nama_supplier' => 'DKK',
                            'nama_sumber' => $smbr,
                            'jenis' => $jns->cJenis,
                            'stok' => $baris->nQty,
                            'harga' => $hrg,
                            'tanggal' => $row->dTanggal,
                            'urutan' => 1,
                        )
                    );
                    DB::table('pkm_masukdtl')->insert(
                        array(
                            'nomor' => $nmr,
                            'id_inventori' => $id,
                            'jumlah' => $baris->nQty,
                            'harga' => $hrg,
                            'keterangan' => $baris->cKeterangan
                        )
                    );
                } else {
                    DB::table('pkm_masukdtl')->insert(
                        array(
                            'nomor' => $nmr,
                            'id_inventori' => $data->id,
                            'jumlah' => $baris->nQty,
                            'harga' => $hrg,
                            'keterangan' => $baris->cKeterangan
                        )
                    );
                    DB::table('pkm_inventori')->where('id', $data->id)->update(array('harga' => $hrg, 'stok' => $data->stok + $baris->nQty, 'tanggal' => $row->dTanggal));
                }
                //update pengurutan barang tabel inventori
                $upd = DB::table('pkm_inventori')->where('id_barang', $baris->cKode)->where('id_puskesmas', $row->cKdPuskesmas)->orderBy('tanggal', 'asc')->get();
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
        }
        echo 'Suksesssss' . count($data);
    }

    public function store_penyesuaian()
    {
        //$cek = DB::table('tbadj')->where('cNomor', Input::get('nmr'))->count();
        $id = Input::get('kode');
        $brg = Input::get('barang');
        $jum = Input::get('jumlah');
        $sat = Input::get('satuan');
        $maks = DB::table('tbadj')->where('cNomor', 'like', 'ADJ-' . date('ym') . '%')->max('cNomor');
        if (strpos($maks, 'ADJ') == false) {
            $kode = 'ADJ-' . date('ym') . sprintf("%04d", 1);
        } else {
            $kode = substr($maks, 0, 8) . sprintf("%04d", substr($maks, 8) + 1);
        }
        DB::table('tbadj')->insert(
            array(
                'cNomor' => $kode,
                'dTanggal' => Input::get('tgl'),
                'lPosted' => 1,
                'cKeterangan' => strtoupper(Input::get('ket'))
            )
        );
        for ($i = 0; $i < count($brg); $i++) {
            $b = explode('-', $sat[$i]);
            $a = explode('-', $id[$i]);
            $data = DB::table('tbinventori')->where('id', $a[1])->first();
            DB::table('tbadjdtl')->insert(
                array(
                    'cNomor' => $kode,
                    'cNoid' => $i + 1,
                    'cKode' => $data->cKode,
                    'cNama' => $data->cNama,
                    'cKdSatuan' => $b[0],
                    'cSatuan' => $b[1],
                    'nQty' => $jum[$i]
                )
            );
            DB::table('tbstock')->insert(
                array(
                    'cNomor' => $kode,
                    'dTanggal' => Input::get('tgl'),
                    'cNoid' => $i + 1,
                    'cKode' => $data->cKode,
                    'cNama' => $data->cNama,
                    'cID' => 'A',
                    'cPos' => 'M',
                    'nQty' => $jum[$i],
                    'nQtyReal' => $jum[$i],
                    'cSatuan' => $b[1]
                )
            );
            DB::table('tbinventori')->where('id', $a[1])->update(array('stok' => ($data->stok + $jum[$i])));
            $upd = DB::table('tbinventori')->where('cKode', $a[1])->orderBy('dTanggal', 'asc')->get();
            $n = 1;
            for ($j = 0; $j < count($upd); $j++) {
                if (count($upd) == 1) {
                    DB::table('pkm_inventori')->where('id', $upd[$j]->id)->update(array('urutan' => 1));
                } else {
                    if ($upd[$j]->stok == 0) {
                        DB::table('tbinventori')->where('id', $upd[$j]->id)->update(array('urutan' => ''));
                    } else {
                        DB::table('tbinventori')->where('id', $upd[$j]->id)->update(array('urutan' => $n));
                        $n++;
                    }
                }
            }
        }
        return Redirect::route('m-penyesuaian')
            ->with('register_success', 'Master Penyesuaian berhasil di-update');
    }

    public function store_pengeluaran()
    {
        $cek = Input::get('edit');
        $pkm = explode('-', Input::get('puskesmas'));
        $bid = explode('-', Input::get('bidang'));
        if (Input::get('barang') != null) {
            $brg = Input::get('barang');
            $jum = Input::get('jumlah');
            $sat = Input::get('satuan');
            $ket = Input::get('keterangan');
            if ($cek == '0') {
                $maks = DB::table('tbkeluar')->where('cNomor', 'like', 'KLR-' . date('ym') . '%')->max('cNomor');
                if ($maks == '') {
                    $kode = 'KLR-' . date('ym') . sprintf("%04d", 1);
                } else {
                    $kode = substr($maks, 0, 8) . sprintf("%04d", substr($maks, 8) + 1);
                }
                DB::table('tbkeluar')->insert(
                    array(
                        'cNomor' => $kode,
                        'dTanggal' => Input::get('tgl'),
                        'cKdBidang' => sprintf("%04d", $bid[0]),
                        'cBidang' => $bid[1],
                        'cKdPuskesmas' => sprintf("%04d", $pkm[0]),
                        'cPuskesmas' => $pkm[1],
                        'lPosted' => 1,
                        'cKeterangan' => strtoupper(Input::get('ket'))
                    )
                );
            } else {
                DB::table('tbkeluar')->where('cNomor', Input::get('nmr'))->update(
                    array(
                        'dTanggal' => Input::get('tgl'),
                        'cKdBidang' => sprintf("%04d", $bid[0]),
                        'cBidang' => $bid[1],
                        'cKdPuskesmas' => sprintf("%04d", $pkm[0]),
                        'cPuskesmas' => $pkm[1],
                        'lPosted' => 1,
                        'cKeterangan' => strtoupper(Input::get('ket'))
                    )
                );
                DB::table('tbstock')->where('cNomor', Input::get('nmr'))->delete();
                $klr = DB::table('tbkeluardtl')->where('cNomor', Input::get('nmr'))->get();
                //update urutan inventori per barang
                for ($i = 0; $i < count($klr); $i++) {
                    $tmp = DB::table('tbinventori')->where('id', $klr[$i]->inventori)->first();
                    if (count($tmp) == 0) {
                        $upd = DB::table('tbinventori')->where('cKode', $klr[$i]->cKode)->where('urutan', '1')->first();
                        DB::table('tbinventori')->where('id', $upd->id)->update(array('stok' => ($upd->stok + $klr[$i]->nQty)));
                    } else {
                        if ($tmp->stok == 0) {
                            $upd = DB::table('tbinventori')->where('cKode', $klr[$i]->cKode)->where('urutan', '<>', '')->get();
                            for ($j = 0; $j < count($upd); $j++) {
                                DB::table('tbinventori')->where('id', $upd[$j]->id)->update(array('urutan' => ($upd[$j]->urutan + 1)));
                            }
                            DB::table('tbinventori')->where('id', $klr[$i]->inventori)->update(array('urutan' => '1', 'stok' => ($tmp->stok + $klr[$i]->nQty)));
                        } else {
                            DB::table('tbinventori')->where('id', $klr[$i]->inventori)->update(array('stok' => ($tmp->stok + $klr[$i]->nQty)));
                        }
                    }
                }
                DB::table('tbkeluardtl')->where('cNomor', Input::get('nmr'))->delete();
                $kode = Input::get('nmr');
            }
            $harga = array();
            for ($i = 0; $i < count($brg); $i++) {
                $a = explode('-', $brg[$i]);
                $b = explode('-', $sat[$i]);
                $temp = $jum[$i];
                $inv = DB::table('tbinventori')->where('cKode', $a[0])->where('urutan', '<>', '')->orderBy('urutan', 'asc')->get();
                for ($j = 0; $j < count($inv); $j++) {
                    if ($temp > 0) {
                        if ($temp < $inv[$j]->stok) {
                            $qty = $temp;
                            $temp = 0;
                        } else {
                            $qty = $inv[$j]->stok;
                            $temp -= $inv[$j]->stok;
                        }
                        DB::table('tbkeluardtl')->insert(
                            array(
                                'cNomor' => $kode,
                                'cNoid' => $i + 1,
                                'cKode' => sprintf("%06d", $a[0]),
                                'cNama' => $a[1],
                                'cKdSatuan' => sprintf("%04d", $b[0]),
                                'cSatuan' => $b[1],
                                'nQty' => $qty,
                                'cKeterangan' => $ket[$i],
                                'inventori' => $inv[$j]->id
                            )
                        );
                        $harga[$i] = $inv[$j]->harga;
                        if (($inv[$j]->stok - $qty) == 0) {
                            $upd = DB::table('tbinventori')->where('cKode', $a[0])->where('urutan', '<>', '')->get();
                            if (count($upd) == 1) {
                                DB::table('tbinventori')->where('id', $inv[$j]->id)->update(array('urutan' => '1'));
                            } else {
                                for ($k = 0; $k < count($upd); $k++) {
                                    DB::table('tbinventori')->where('id', $upd[$k]->id)->update(array('urutan' => ($upd[$k]->urutan - 1)));
                                }
                                DB::table('tbinventori')->where('id', $inv[$j]->id)->update(array('urutan' => ''));
                            }
                            DB::table('tbinventori')->where('id', $inv[$j]->id)->update(array('stok' => ($inv[$j]->stok - $qty)));
                        } else {
                            DB::table('tbinventori')->where('id', $inv[$j]->id)->update(array('stok' => ($inv[$j]->stok - $qty)));
                        }
                    }
                }
                DB::table('tbstock')->insert(
                    array(
                        'cNomor' => $kode,
                        'dTanggal' => Input::get('tgl'),
                        'cNoid' => $i + 1,
                        'cKode' => sprintf("%06d", $a[0]),
                        'cNama' => $a[1],
                        'cID' => 'K',
                        'cPos' => 'K',
                        'nQty' => $jum[$i],
                        'nQtyReal' => $jum[$i] * (-1),
                        'cSatuan' => $b[1]
                    )
                );
            }
            //input data tabel pkm barang
            $k_pkm = (int)$pkm[0];
            if ($k_pkm <= 62 && Input::get('pilih') == '2') {
                $maks = DB::table('pkm_masuk')->where('nomor', 'like', 'MSK' . sprintf("%03d", $k_pkm) . '-' . date('ym') . '%')->max('nomor');
                if ($maks == '') {
                    $nmr = 'MSK' . sprintf("%03d", $k_pkm) . '-' . date('ym') . sprintf("%04d", 1);
                } else {
                    $nmr = substr($maks, 0, 11) . sprintf("%04d", substr($maks, 11) + 1);
                }
                DB::table('pkm_masuk')->insert(
                    array(
                        'nomor' => $nmr,
                        'tanggal' => Input::get('tgl'),
                        'id_puskesmas' => sprintf("%04d", $pkm[0]),
                        'nama_puskesmas' => $pkm[1],
                        'id_supplier' => sprintf("%04d", 9999),
                        'nama_Supplier' => 'DKK',
                        'id_sumber' => sprintf("%04d", 1),
                        'nama_sumber' => 'APBD',
                        'tahun' => date('Y'),
                        'keterangan' => strtoupper(Input::get('ket')),
                        'status' => 1,
                        'read' => 1
                    )
                );
                for ($i = 0; $i < count($brg); $i++) {
                    $a = explode('-', $brg[$i]);
                    $b = explode('-', $sat[$i]);
                    if (strpos($a[1], 'BANTUAN') !== false) {
                        DB::table('pkm_masuk')->where('nomor', $nmr)->update(array('id_sumber' => sprintf("%04d", 2), 'nama_sumber' => 'APBN'));
                        $smbr = 'APBN';
                    } else {
                        $smbr = 'APBD';
                    }
                    $data = DB::table('pkm_inventori')->where('id_barang', $a[0])->where('id_puskesmas', $pkm[0])->where('id_supplier', '9999')->first();
                    if (count($data) == 0) {
                        $jns = DB::table('tbbarang')->where('cKode', $a[0])->select('cJenis')->first();
                        $id = DB::table('pkm_inventori')->insertGetId(
                            array(
                                'id_barang' => sprintf("%06d", $a[0]),
                                'nama_barang' => $a[1],
                                'id_satuan' => sprintf("%04d", $b[0]),
                                'nama_satuan' => $b[1],
                                'id_puskesmas' => sprintf("%04d", $pkm[0]),
                                'nama_puskesmas' => $pkm[1],
                                'id_supplier' => sprintf("%04d", 9999),
                                'nama_supplier' => 'DKK',
                                'nama_sumber' => $smbr,
                                'jenis' => $jns->cJenis,
                                'stok' => $jum[$i],
                                'harga' => $harga[$i],
                                'tanggal' => Input::get('tgl'),
                                'urutan' => 1,
                            )
                        );
                        DB::table('pkm_masukdtl')->insert(
                            array(
                                'nomor' => $nmr,
                                'id_inventori' => $id,
                                'jumlah' => $jum[$i],
                                'harga' => $harga[$i],
                                'keterangan' => $ket[$i]
                            )
                        );
                    } else {
                        DB::table('pkm_masukdtl')->insert(
                            array(
                                'nomor' => $nmr,
                                'id_inventori' => $data->id,
                                'jumlah' => $jum[$i],
                                'harga' => $harga[$i],
                                'keterangan' => $ket[$i]
                            )
                        );
                        DB::table('pkm_inventori')->where('id', $data->id)->update(array('harga' => $harga[$i], 'stok' => $data->stok + $jum[$i], 'tanggal' => Input::get('tgl')));
                    }
                    //update pengurutan barang tabel inventori
                    $upd = DB::table('pkm_inventori')->where('id_barang', $a[0])->where('id_puskesmas', $pkm[0])->orderBy('tanggal', 'asc')->get();
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

            }
            return Redirect::route('m-pengeluaran')
                ->with('register_success', 'Master Pengeluaran berhasil di-update');
        } else {
            return Redirect::route('m-pengeluaran')
                ->with('register_failed', 'Perubahan data GAGAL');
        }
    }

    public function store_penerimaan()
    {
        $cek = Input::get('edit');
        $sup = explode('-', Input::get('supplier'));
        $sum = explode('-', Input::get('sumber'));
        $bid = explode('-', Input::get('bidang'));
        $brg = Input::get('barang');
        $jum = Input::get('jumlah');
        $sat = Input::get('satuan');
        $h_sat = Input::get('h_satuan');
        $tot = Input::get('total');
        if ($cek == '0') {
            $maks = DB::table('tbterima')->where('cNomor', 'like', 'TRM-' . date('ym') . '%')->max('cNomor');
            if ($maks == '') {
                $maks = 'TRM-' . date('ym') . sprintf("%04d", 1);
            } else {
                $maks = substr($maks, 0, 8) . sprintf("%04d", substr($maks, 8) + 1);
            }
            DB::table('tbterima')->insert(
                array(
                    'cNomor' => $maks,
                    'dTanggal' => Input::get('tgl'),
                    'cKdSupplier' => sprintf("%04d", $sup[0]),
                    'cSupplier' => $sup[1],
                    'cKdSumber' => sprintf("%04d", $sum[0]),
                    'cSumber' => $sum[1],
                    'cKdBidang' => sprintf("%04d", $bid[0]),
                    'cBidang' => $bid[1],
                    'cTahunPengadaan' => Input::get('tahun'),
                    'lPosted' => 1,
                    'cKeterangan' => strtoupper(Input::get('ket')),
                    'cNoBukti' => Input::get('nmr_bukti'),
                    'dTglBukti' => Input::get('tgl_bukti'),
                    'cJnsSurat' => strtoupper(Input::get('jenis_surat')),
                    'cNoSurat' => Input::get('nmr_surat'),
                    'dTglSurat' => Input::get('tgl_surat'),
                    'cNoAcara' => Input::get('nmr_acara'),
                    'dTglAcara' => Input::get('tgl_acara')
                )
            );
            $kode = $maks;
        } else {
            DB::table('tbterima')->where('cNomor', Input::get('nmr'))->update(
                array(
                    'dTanggal' => Input::get('tgl'),
                    'cKdSupplier' => sprintf("%04d", $sup[0]),
                    'cSupplier' => $sup[1],
                    'cKdSumber' => sprintf("%04d", $sum[0]),
                    'cSumber' => $sum[1],
                    'cKdBidang' => sprintf("%04d", $bid[0]),
                    'cBidang' => $bid[1],
                    'cTahunPengadaan' => Input::get('tahun'),
                    'lPosted' => 1,
                    'cKeterangan' => strtoupper(Input::get('ket')),
                    'cNoBukti' => Input::get('nmr_bukti'),
                    'dTglBukti' => Input::get('tgl_bukti'),
                    'cJnsSurat' => strtoupper(Input::get('jenis_surat')),
                    'cNoSurat' => Input::get('nmr_surat'),
                    'dTglSurat' => Input::get('tgl_surat'),
                    'cNoAcara' => Input::get('nmr_acara'),
                    'dTglAcara' => Input::get('tgl_acara')
                )
            );
            DB::table('tbterimadtl')->where('cNomor', Input::get('nmr'))->delete();
            DB::table('tbstock')->where('cNomor', Input::get('nmr'))->delete();
            $kode = Input::get('nmr');
        }
        for ($i = 0; $i < count($brg); $i++) {
            $a = explode('-', $brg[$i]);
            $b = explode('-', $sat[$i]);
            DB::table('tbterimadtl')->insert(
                array(
                    'cNomor' => $kode,
                    'cNoid' => $i + 1,
                    'cKode' => sprintf("%06d", $a[0]),
                    'cNama' => $a[1],
                    'cKdSatuan' => sprintf("%04d", $b[0]),
                    'cSatuan' => $b[1],
                    'nQty' => $jum[$i],
                    'nHarga' => $h_sat[$i],
                    'nSTotal' => $tot[$i]
                )
            );
            DB::table('tbstock')->insert(
                array(
                    'cNomor' => $kode,
                    'dTanggal' => Input::get('tgl'),
                    'cNoid' => $i + 1,
                    'cKode' => sprintf("%06d", $a[0]),
                    'cNama' => $a[1],
                    'cID' => 'T',
                    'cPos' => 'M',
                    'nQty' => $jum[$i],
                    'nQtyReal' => $jum[$i],
                    'cSatuan' => $b[1],
                    'nHarga' => $h_sat[$i]
                )
            );
            $s_inv = DB::table('tbinventori')->where('cKode', $a[0])->where('cKdSupplier', $sup[0])->count();
            $inv = DB::table('tbinventori')->where('cKode', $a[0])->where('cKdSupplier', $sup[0])->first();
            $mx = DB::table('tbinventori')->where('cKode', $a[0])->max('urutan');
            if ($s_inv > 0) {
                DB::table('tbinventori')->where('cKode', $a[0])->where('cKdSupplier', $sup[0])
                    ->update(array('stok' => ($inv->stok + $jum[$i]), 'harga' => $h_sat[$i], 'dTanggal' => Input::get('tgl')));
            } else {
                DB::table('tbinventori')->insert(
                    array(
                        'cKode' => sprintf("%06d", $a[0]),
                        'cNama' => $a[1],
                        'cKdSupplier' => sprintf("%04d", $sup[0]),
                        'cSupplier' => $sup[1],
                        'stok' => $jum[$i],
                        'harga' => $h_sat[$i],
                        'dTanggal' => Input::get('tgl'),
                        'urutan' => $mx + 1
                    )
                );
            }
            $upd = DB::table('tbinventori')->where('cKode', $a[0])->orderBy('dTanggal', 'asc')->get();
            $n = 1;
            for ($j = 0; $j < count($upd); $j++) {
                if (count($upd) == 1) {
                    DB::table('tbinventori')->where('id', $upd[$j]->id)->update(array('urutan' => 1));
                } else {
                    if ($upd[$j]->stok == 0) {
                        DB::table('tbinventori')->where('id', $upd[$j]->id)->update(array('urutan' => ''));
                    } else {
                        DB::table('tbinventori')->where('id', $upd[$j]->id)->update(array('urutan' => $n));
                        $n++;
                    }
                }
            }
        }
        return Redirect::route('m-penerimaan')
            ->with('register_success', 'Master Penerimaan berhasil di-update');
    }

    public function store_barang()
    {
        $a = explode('-', Input::get('jenis'));
        $b = explode('-', Input::get('merk'));
        $c = explode('-', Input::get('tipe'));
        $d = explode('-', Input::get('satuan'));
        if (Input::get('id') == "") {
            $maks = DB::table('pkm_barang')->where('cKode','<','100000')->max('cKode');
            DB::table('pkm_barang')->insert(
                array(
                    'cKode' => sprintf("%06d", $maks + 1),
                    'cNama' => strtoupper(Input::get('nama')),
                    'cKdJenis' => $a[0],
                    'cJenis' => $a[1],
                    'cKdMerk' => sprintf("%04d", $b[0]),
                    'cMerk' => $b[1],
                    'cKdTipe' => sprintf("%04d", $c[0]),
                    'cTipe' => $c[1],
                    'cKdSatuan' => sprintf("%04d", $d[0]),
                    'cSatuan' => $d[1],
                    'ikondisi' => Input::get('kondisi')
                )
            );
            if(Input::get('jkn')!==null){
                DB::table('pkm_barang')->insert(
                    array(
                        'cKode' => '1'.sprintf("%05d", $maks + 1),
                        'cNama' => strtoupper(Input::get('nama')).' [JKN]',
                        'cKdJenis' => $a[0],
                        'cJenis' => $a[1],
                        'cKdMerk' => sprintf("%04d", $b[0]),
                        'cMerk' => $b[1],
                        'cKdTipe' => sprintf("%04d", $c[0]),
                        'cTipe' => $c[1],
                        'cKdSatuan' => sprintf("%04d", $d[0]),
                        'cSatuan' => $d[1],
                        'ikondisi' => Input::get('kondisi')
                    )
                );
            }
        } else {
            DB::table('pkm_barang')
                ->where('cKode', sprintf("%06d", Input::get('id')))
                ->update(array(
                        'cNama' => strtoupper(Input::get('nama')),
                        'cKdJenis' => sprintf("%04d", $a[0]),
                        'cJenis' => $a[1],
                        'cKdMerk' => sprintf("%04d", $b[0]),
                        'cMerk' => $b[1],
                        'cKdTipe' => sprintf("%04d", $c[0]),
                        'cTipe' => $c[1],
                        'cKdSatuan' => sprintf("%04d", $d[0]),
                        'cSatuan' => $d[1],
                        'ikondisi' => Input::get('kondisi')
                )
            );
            $cek=DB::table('pkm_barang')->where('cKode', '1'.sprintf("%05d", Input::get('id')))->first();
            if(Input::get('jkn')!==null){                
                if(count($cek)>0){
                    DB::table('pkm_barang')
                        ->where('cKode', '1'.sprintf("%05d", Input::get('id')))
                        ->update(array(
                            'cNama' => strtoupper(Input::get('nama')).' [JKN]',
                            'cKdJenis' => sprintf("%04d", $a[0]),
                            'cJenis' => $a[1],
                            'cKdMerk' => sprintf("%04d", $b[0]),
                            'cMerk' => $b[1],
                            'cKdTipe' => sprintf("%04d", $c[0]),
                            'cTipe' => $c[1],
                            'cKdSatuan' => sprintf("%04d", $d[0]),
                            'cSatuan' => $d[1],
                            'ikondisi' => Input::get('kondisi')
                        )
                    );
                }else{
                    DB::table('pkm_barang')->insert(
                        array(
                            'cKode' => '1'.sprintf("%05d", Input::get('id')),
                            'cNama' => strtoupper(Input::get('nama')).' [JKN]',
                            'cKdJenis' => $a[0],
                            'cJenis' => $a[1],
                            'cKdMerk' => sprintf("%04d", $b[0]),
                            'cMerk' => $b[1],
                            'cKdTipe' => sprintf("%04d", $c[0]),
                            'cTipe' => $c[1],
                            'cKdSatuan' => sprintf("%04d", $d[0]),
                            'cSatuan' => $d[1],
                            'ikondisi' => Input::get('kondisi')
                        )
                    );
                }
            }else{
                if($cek>0){
                    DB::table('pkm_barang')->where('cKode', '1'.sprintf("%05d", Input::get('id')))->delete();                        
                }
            }
        }
        return Redirect::route('m-barang')
            ->with('register_success', 'Master Barang berhasil di-update');
    }

    public function store_puskesmas()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('tbpuskesmas')->max('cKode');
            DB::table('tbpuskesmas')->insert(
                array(
                    'cKode' => sprintf("%04d", $maks + 1),
                    'cNama' => strtoupper(Input::get('nama')),
                    'cAlamat' => Input::get('alamat'),
                    'cTelp' => Input::get('telp'),
                    'cFax' => Input::get('fax'),
                    'cPimpinan' => Input::get('pimpinan')
                )
            );
        } else {
            DB::table('tbpuskesmas')
                ->where('id', sprintf("%04d", Input::get('cKode')))
                ->update(array(
                        'cNama' => strtoupper(Input::get('nama')),
                        'cAlamat' => Input::get('alamat'),
                        'cTelp' => Input::get('telp'),
                        'cFax' => Input::get('fax'),
                        'cPimpinan' => Input::get('pimpinan')
                    )
                );
        }

        return Redirect::route('m-puskesmas')
            ->with('register_success', 'Master Puskesmas berhasil di-update');
        // 2b. jika tidak, kembali ke halaman form registrasi
    }

    public function store_supplier()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('tbsupplier')->max('cKode');
            DB::table('tbsupplier')->insert(
                array(
                    'cKode' => sprintf("%04d", $maks + 1),
                    'cNama' => strtoupper(Input::get('nama')),
                    'cAlamat' => strtoupper(Input::get('alamat')),
                    'cKota' => strtoupper(Input::get('kota')),
                    'cTelp' => Input::get('telp'),
                    'cFax' => Input::get('fax'),
                    'cKeterangan' => strtoupper(Input::get('keterangan'))
                )
            );
        } else {
            DB::table('tbsupplier')
                ->where('cKode', sprintf("%04d", Input::get('id')))
                ->update(array(
                        'cNama' => strtoupper(Input::get('nama')),
                        'cAlamat' => strtoupper(Input::get('alamat')),
                        'cKota' => strtoupper(Input::get('kota')),
                        'cTelp' => Input::get('telp'),
                        'cFax' => Input::get('fax'),
                        'cKeterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
        }

        return Redirect::route('m-supplier')
            ->with('register_success', 'Master Supplier berhasil di-update');
        // 2b. jika tidak, kembali ke halaman form registrasi
    }

    public function store_sumberanggaran()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('tbsumberanggaran')->max('cKode');
            DB::table('tbsumberanggaran')->insert(
                array(
                    'cKode' => sprintf("%04d", $maks + 1),
                    'cNama' => strtoupper(Input::get('nama')),
                    'cAsal' => strtoupper(Input::get('asal'))
                )
            );
        } else {
            DB::table('tbsumberanggaran')->where('cKode', sprintf("%04d", Input::get('id')))->update(array('cNama' => strtoupper(Input::get('nama')), 'cAsal' => strtoupper(Input::get('asal'))));
        }

        return Redirect::route('m-sumberanggaran')
            ->with('register_success', 'Master Sumber Anggaran berhasil di-update');
        // 2b. jika tidak, kembali ke halaman form registrasi
    }

    public function store_jenis()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('tbjenis')->max('cKode');
            DB::table('tbjenis')->insert(
                array(
                    'cKode' => sprintf("%04d", $maks + 1),
                    'cNama' => strtoupper(Input::get('nama'))
                )
            );
        } else {
            DB::table('tbjenis')->where('cKode', sprintf("%04d", Input::get('id')))->update(array('cNama' => strtoupper(Input::get('nama'))));
        }

        return Redirect::route('m-jenis')
            ->with('register_success', 'Master Jenis berhasil di-update');
        // 2b. jika tidak, kembali ke halaman form registrasi
    }

    public function store_satuan()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('tbsatuan')->max('cKode');
            DB::table('tbsatuan')->insert(
                array(
                    'cKode' => sprintf("%04d", $maks + 1),
                    'cNama' => strtoupper(Input::get('nama'))
                )
            );
        } else {
            DB::table('tbsatuan')->where('cKode', sprintf("%04d", Input::get('id')))->update(array('cNama' => strtoupper(Input::get('nama'))));
        }
        return Redirect::route('m-satuan')
            ->with('register_success', 'Master Satuan berhasil di-update');
    }

    public function store_tipe()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('tbtipe')->max('ckode');
            DB::table('tbtipe')->insert(
                array(
                    'ckode' => sprintf("%04d", $maks + 1),
                    'cNama' => strtoupper(Input::get('nama'))
                )
            );
        } else {
            DB::table('tbtipe')->where('ckode', sprintf("%04d", Input::get('id')))->update(array('cNama' => strtoupper(Input::get('nama'))));
        }
        return Redirect::route('m-tipe')
            ->with('register_success', 'Master Tipe berhasil di-update');
    }

    public function store_merk()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('tbmerk')->max('cKode');
            DB::table('tbmerk')->insert(
                array(
                    'cKode' => sprintf("%04d", $maks + 1),
                    'cNama' => strtoupper(Input::get('nama'))
                )
            );
        } else {
            DB::table('tbmerk')->where('ckode', sprintf("%04d", Input::get('id')))->update(array('cNama' => strtoupper(Input::get('nama'))));
        }
        return Redirect::route('m-merk')
            ->with('register_success', 'Master Merk berhasil di-update');
    }

    //Aplikasi Puskesmas
    //ASET
    public function entry_aset()
    {
        $aset = Input::get('aset');
        $tab = 'aset_data';
        $subkel = explode('-', Input::get('subkel'));
        $maks = DB::table($tab)->insertGetId(
            array(
                'id_puskesmas' => sprintf("%03d", Auth::user()->id_puskesmas),
                'nama_puskesmas' => Auth::user()->nama_puskesmas,
                'kode_bidang' => $subkel[0],
                'desc_bidang' => $subkel[1],
                'kode_perwali' => Input::get('kode_perwali'),
                'no_register' => Input::get('no_reg'),
                'nama' => strtoupper(Input::get('nama')),
                'jumlah' => Input::get('jumlah'),
                'satuan' => strtoupper(Input::get('satuan')),
                'h_satuan' => Input::get('h_satuan'),
                'ppn' => Input::get('ppn'),
                'tipe_aset' => $aset,
                'valid' => '0'
            )
        );
        switch ($aset) {
            case 'tanah':
                $id = DB::table('aset_data')->where('id_aset', 'like', 'A' . sprintf("%03d", Auth::user()->id_puskesmas) . '%')->max('id_aset');
                if ($id == '') {
                    $baru = 'A' . sprintf("%03d", Auth::user()->id_puskesmas) . sprintf("%04d", 1);
                } else {
                    $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                }
                DB::table('aset_tanah')->insert(
                    array(
                        'id_aset' => $baru,
                        'luas' => Input::get('luas'),
                        'tahun' => Input::get('tahun'),
                        'alamat' => strtoupper(Input::get('alamat')),
                        'status' => strtoupper(Input::get('status')),
                        'no_sertifikat' => Input::get('no_sertifikat'),
                        'tgl_sertifikat' => Input::get('tgl_sertifikat'),
                        'nama_sertifikat' => strtoupper(Input::get('nama_sertifikat')),
                        'fungsi' => strtoupper(Input::get('fungsi')),
                        'asal' => strtoupper(Input::get('asal')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'mesin':
                $id = DB::table('aset_data')->where('id_aset', 'like', 'B' . sprintf("%03d", Auth::user()->id_puskesmas) . '%')->max('id_aset');
                if ($id == '') {
                    $baru = 'B' . sprintf("%03d", Auth::user()->id_puskesmas) . sprintf("%04d", 1);
                } else {
                    $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                }
                DB::table('aset_mesin')->insert(
                    array(
                        'id_aset' => $baru,
                        'ruangan' => strtoupper(Input::get('ruangan')),
                        'merk' => strtoupper(Input::get('merk')),
                        'tipe' => strtoupper(Input::get('tipe')),
                        'ukuran' => Input::get('ukuran'),
                        'b_warna' => strtoupper(Input::get('b_warna')),
                        'no_bpkb' => Input::get('no_bpkb'),
                        'no_polisi' => Input::get('no_polisi'),
                        'no_rangka' => Input::get('no_rangka'),
                        'no_mesin' => Input::get('no_mesin'),
                        'warna' => strtoupper(Input::get('warna')),
                        'cc' => Input::get('cc'),
                        'no_stnk' => Input::get('no_stnk'),
                        'tgl_stnk' => Input::get('tgl_stnk'),
                        'bbm' => strtoupper(Input::get('bbm')),
                        't_pengadaan' => Input::get('t_pengadaan'),
                        't_perakitan' => Input::get('t_perakitan'),
                        'asal' => strtoupper(Input::get('asal')),
                        'nama_pj' => strtoupper(Input::get('nama_pj')),
                        'jabatan_pj' => strtoupper(Input::get('jabatan_pj')),
                        'kondisi' => strtoupper(Input::get('kondisi')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'bangunan':
                $id = DB::table('aset_data')->where('id_aset', 'like', 'C' . sprintf("%03d", Auth::user()->id_puskesmas) . '%')->max('id_aset');
                if ($id == '') {
                    $baru = 'C' . sprintf("%03d", Auth::user()->id_puskesmas) . sprintf("%04d", 1);
                } else {
                    $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                }
                DB::table('aset_bangunan')->insert(
                    array(
                        'id_aset' => $baru,
                        'tahun' => Input::get('tahun'),
                        'alamat' => strtoupper(Input::get('alamat')),
                        'tipe' => strtoupper(Input::get('tipe')),
                        'j_bahan' => strtoupper(Input::get('j_bahan')),
                        'j_kontruksi' => strtoupper(Input::get('j_kontruksi')),
                        'l_lantai' => Input::get('l_lantai'),
                        'l_bangunan' => Input::get('l_bangunan'),
                        'jml_lantai' => Input::get('jml_lantai'),
                        'fungsi' => strtoupper(Input::get('fungsi')),
                        'asal' => strtoupper(Input::get('asal')),
                        'no_reg_tanah' => Input::get('no_reg_tanah'),
                        'l_tanah' => Input::get('luas_tanah'),
                        's_tanah' => strtoupper(Input::get('status_tanah')),
                        'dok' => strtoupper(Input::get('dok')),
                        'no_dok' => Input::get('no_dok'),
                        'kondisi' => strtoupper(Input::get('kondisi')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'jalan':
                $id = DB::table('aset_data')->where('id_aset', 'like', 'D' . sprintf("%03d", Auth::user()->id_puskesmas) . '%')->max('id_aset');
                if ($id == '') {
                    $baru = 'D' . sprintf("%03d", Auth::user()->id_puskesmas) . sprintf("%04d", 1);
                } else {
                    $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                }
                DB::table('aset_jalan')->insert(
                    array(
                        'id_aset' => $baru,
                        'tahun' => Input::get('tahun'),
                        'alamat' => strtoupper(Input::get('alamat')),
                        'tipe' => strtoupper(Input::get('tipe')),
                        'j_bahan' => strtoupper(Input::get('j_bahan')),
                        'j_kontruksi' => strtoupper(Input::get('j_kontruksi')),
                        'panjang' => Input::get('panjang'),
                        'lebar' => Input::get('lebar'),
                        'luas' => Input::get('luas'),
                        'fungsi' => strtoupper(Input::get('fungsi')),
                        'asal' => strtoupper(Input::get('asal')),
                        'no_reg_tanah' => Input::get('no_reg_tanah'),
                        'l_tanah' => Input::get('luas_tanah'),
                        's_tanah' => strtoupper(Input::get('status_tanah')),
                        'dok' => strtoupper(Input::get('dok')),
                        'no_dok' => Input::get('no_dok'),
                        'kondisi' => strtoupper(Input::get('kondisi')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'tetaplain':
                $id = DB::table('aset_data')->where('id_aset', 'like', 'E' . sprintf("%03d", Auth::user()->id_puskesmas) . '%')->max('id_aset');
                if ($id == '') {
                    $baru = 'E' . sprintf("%03d", Auth::user()->id_puskesmas) . sprintf("%04d", 1);
                } else {
                    $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                }
                DB::table('aset_tetaplain')->insert(
                    array(
                        'id_aset' => $baru,
                        'ruangan' => strtoupper(Input::get('ruangan')),
                        'tahun' => Input::get('tahun'),
                        'judul' => strtoupper(Input::get('judul')),
                        'pengarang' => strtoupper(Input::get('pengarang')),
                        'pencipta' => strtoupper(Input::get('pencipta')),
                        'daerah' => strtoupper(Input::get('daerah')),
                        'bahan' => strtoupper(Input::get('bahan')),
                        'jenis' => strtoupper(Input::get('jenis')),
                        'ukuran' => strtoupper(Input::get('ukuran')),
                        'fungsi' => strtoupper(Input::get('fungsi')),
                        'asal' => strtoupper(Input::get('asal')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'lain':
                $id = DB::table('aset_data')->where('id_aset', 'like', 'F' . sprintf("%03d", Auth::user()->id_puskesmas) . '%')->max('id_aset');
                if ($id == '') {
                    $baru = 'F' . sprintf("%03d", Auth::user()->id_puskesmas) . sprintf("%04d", 1);
                } else {
                    $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                }
                DB::table('aset_lain')->insert(
                    array(
                        'id_aset' => $baru,
                        'ruangan' => strtoupper(Input::get('ruangan')),
                        'tahun' => Input::get('tahun'),
                        'merk' => strtoupper(Input::get('merk')),
                        'tipe' => strtoupper(Input::get('tipe')),
                        'no_seri' => strtoupper(Input::get('no_seri')),
                        'asal' => strtoupper(Input::get('asal')),
                        'kondisi' => strtoupper(Input::get('kondisi')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
        }
        DB::table($tab)->where('id', $maks)->update(array('id_aset' => $baru));
        return Response::json(array('id' => $maks, 'id_aset' => $baru));
    }

    public function edit_aset()
    {
        $aset = Input::get('aset');
        $id_aset = Input::get('ast');
        $tab = 'aset_data';
        $subkel = explode('#', Input::get('subkel'));
        DB::table($tab)->where('id_aset', $id_aset)->where('id_ruangan', '')->update(array('jumlah' => Input::get('jumlah')));
        DB::table($tab)->where('id_aset', $id_aset)->update(
            array(
                'id_puskesmas' => sprintf("%03d", Auth::user()->id_puskesmas),
                'nama_puskesmas' => Auth::user()->nama_puskesmas,
                'kode_bidang' => $subkel[0],
                'desc_bidang' => $subkel[1],
                'kode_perwali' => Input::get('kode_perwali'),
                'no_register' => Input::get('no_reg'),
                'nama' => strtoupper(Input::get('nama')),
                'satuan' => strtoupper(Input::get('satuan')),
                'h_satuan' => Input::get('h_satuan'),
                'ppn' => Input::get('ppn'),
                'tipe_aset' => $aset,
                'valid' => '0'
            )
        );
        switch ($aset) {
            case 'tanah':
                DB::table('aset_tanah')->where('id_aset', $id_aset)->update(
                    array(
                        'luas' => Input::get('luas'),
                        'tahun' => Input::get('tahun'),
                        'alamat' => strtoupper(Input::get('alamat')),
                        'status' => strtoupper(Input::get('status')),
                        'no_sertifikat' => Input::get('no_sertifikat'),
                        'tgl_sertifikat' => Input::get('tgl_sertifikat'),
                        'nama_sertifikat' => strtoupper(Input::get('nama_sertifikat')),
                        'fungsi' => strtoupper(Input::get('fungsi')),
                        'asal' => strtoupper(Input::get('asal')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'mesin':
                DB::table('aset_mesin')->where('id_aset', $id_aset)->update(
                    array(
                        'ruangan' => strtoupper(Input::get('ruangan')),
                        'merk' => strtoupper(Input::get('merk')),
                        'tipe' => strtoupper(Input::get('tipe')),
                        'ukuran' => Input::get('ukuran'),
                        'b_warna' => strtoupper(Input::get('b_warna')),
                        'no_bpkb' => Input::get('no_bpkb'),
                        'no_polisi' => Input::get('no_polisi'),
                        'no_rangka' => Input::get('no_rangka'),
                        'no_mesin' => Input::get('no_mesin'),
                        'warna' => strtoupper(Input::get('warna')),
                        'cc' => Input::get('cc'),
                        'no_stnk' => Input::get('no_stnk'),
                        'tgl_stnk' => Input::get('tgl_stnk'),
                        'bbm' => strtoupper(Input::get('bbm')),
                        't_pengadaan' => Input::get('t_pengadaan'),
                        't_perakitan' => Input::get('t_perakitan'),
                        'asal' => strtoupper(Input::get('asal')),
                        'nama_pj' => strtoupper(Input::get('nama_pj')),
                        'jabatan_pj' => strtoupper(Input::get('jabatan_pj')),
                        'kondisi' => strtoupper(Input::get('kondisi')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'bangunan':
                DB::table('aset_bangunan')->where('id_aset', $id_aset)->update(
                    array(
                        'tahun' => Input::get('tahun'),
                        'alamat' => strtoupper(Input::get('alamat')),
                        'tipe' => strtoupper(Input::get('tipe')),
                        'j_bahan' => strtoupper(Input::get('j_bahan')),
                        'j_kontruksi' => strtoupper(Input::get('j_kontruksi')),
                        'l_lantai' => Input::get('l_lantai'),
                        'l_bangunan' => Input::get('l_bangunan'),
                        'jml_lantai' => Input::get('jml_lantai'),
                        'fungsi' => strtoupper(Input::get('fungsi')),
                        'asal' => strtoupper(Input::get('asal')),
                        'no_reg_tanah' => Input::get('no_reg_tanah'),
                        'l_tanah' => Input::get('luas_tanah'),
                        's_tanah' => strtoupper(Input::get('status_tanah')),
                        'dok' => strtoupper(Input::get('dok')),
                        'no_dok' => Input::get('no_dok'),
                        'kondisi' => strtoupper(Input::get('kondisi')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'jalan':
                DB::table('aset_jalan')->where('id_aset', $id_aset)->update(
                    array(
                        'tahun' => Input::get('tahun'),
                        'alamat' => strtoupper(Input::get('alamat')),
                        'tipe' => strtoupper(Input::get('tipe')),
                        'j_bahan' => strtoupper(Input::get('j_bahan')),
                        'j_kontruksi' => strtoupper(Input::get('j_kontruksi')),
                        'panjang' => Input::get('panjang'),
                        'lebar' => Input::get('lebar'),
                        'luas' => Input::get('luas'),
                        'fungsi' => strtoupper(Input::get('fungsi')),
                        'asal' => strtoupper(Input::get('asal')),
                        'no_reg_tanah' => Input::get('no_reg_tanah'),
                        'l_tanah' => Input::get('luas_tanah'),
                        's_tanah' => strtoupper(Input::get('status_tanah')),
                        'dok' => strtoupper(Input::get('dok')),
                        'no_dok' => Input::get('no_dok'),
                        'kondisi' => strtoupper(Input::get('kondisi')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'tetaplain':
                DB::table('aset_tetaplain')->where('id_aset', $id_aset)->update(
                    array(
                        'ruangan' => strtoupper(Input::get('ruangan')),
                        'tahun' => Input::get('tahun'),
                        'judul' => strtoupper(Input::get('judul')),
                        'pengarang' => strtoupper(Input::get('pengarang')),
                        'pencipta' => strtoupper(Input::get('pencipta')),
                        'daerah' => strtoupper(Input::get('daerah')),
                        'bahan' => strtoupper(Input::get('bahan')),
                        'jenis' => strtoupper(Input::get('jenis')),
                        'ukuran' => strtoupper(Input::get('ukuran')),
                        'fungsi' => strtoupper(Input::get('fungsi')),
                        'asal' => strtoupper(Input::get('asal')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
            case 'lain':
                DB::table('aset_lain')->where('id_aset', $id_aset)->update(
                    array(
                        'ruangan' => strtoupper(Input::get('ruangan')),
                        'tahun' => Input::get('tahun'),
                        'merk' => strtoupper(Input::get('merk')),
                        'tipe' => strtoupper(Input::get('tipe')),
                        'no_seri' => strtoupper(Input::get('no_seri')),
                        'asal' => strtoupper(Input::get('asal')),
                        'kondisi' => strtoupper(Input::get('kondisi')),
                        'keterangan' => strtoupper(Input::get('keterangan'))
                    )
                );
                break;
        }
        return Redirect::route('aset-edit');
    }

    public function store_asetmasuk()
    {
        $cek = Input::get('edit');
        $sup = explode('-', Input::get('supplier'));
        $sum = explode('-', Input::get('sumber'));
        $bid = explode('-', Input::get('bidang'));
        //$ruang = explode('-', Input::get('ruangan'));
        $brg = Input::get('barang');
        if ($cek == '0') {
            $maks = DB::table('aset_masuk')->where('nomor', 'like', 'AM' . sprintf("%03d", Auth::user()->id_puskesmas) . '-' . date('ym') . '%')->max('nomor');
            if ($maks == '') {
                $maks = 'AM' . sprintf("%03d", Auth::user()->id_puskesmas) . '-' . date('ym') . sprintf("%04d", 1);
            } else {
                $maks = substr($maks, 0, 10) . sprintf("%04d", substr($maks, 10) + 1);
            }
            DB::table('aset_masuk')->insert(
                array(
                    'nomor' => $maks,
                    'tanggal' => Input::get('tgl'),
                    'jenis' => Input::get('trm'),
                    'id_supplier' => sprintf("%04d", $sup[0]),
                    'nama_supplier' => $sup[1],
                    'id_sumber' => sprintf("%04d", $sum[0]),
                    'nama_sumber' => $sum[1],
                    'id_unit' => sprintf("%04d", $bid[0]),
                    'nama_unit' => $bid[1],
                    'id_puskesmas' => Auth::user()->id_puskesmas,
                    'nama_puskesmas' => Auth::user()->nama_puskesmas,
                    'tahun' => Input::get('tahun'),
                    'keterangan' => strtoupper(Input::get('ket')),
                    'no_bukti' => Input::get('nmr_bukti'),
                    'tgl_bukti' => Input::get('tgl_bukti'),
                    'jenis_surat' => strtoupper(Input::get('jenis_surat')),
                    'no_surat' => Input::get('nmr_surat'),
                    'tgl_surat' => Input::get('tgl_surat'),
                    'no_acara' => Input::get('nmr_acara'),
                    'tgl_acara' => Input::get('tgl_acara'),
                    'status' => 0
                )
            );
            $kode = $maks;
        } else {
            //$as = DB::table('aset_masuk')->where('nomor', Input::get('nmr'))->first();
            DB::table('aset_masuk')->where('nomor', Input::get('nmr'))->update(
                array(
                    'tanggal' => Input::get('tgl'),
                    'jenis' => Input::get('trm'),
                    'id_supplier' => sprintf("%04d", $sup[0]),
                    'nama_supplier' => $sup[1],
                    'id_sumber' => sprintf("%04d", $sum[0]),
                    'nama_sumber' => $sum[1],
                    'id_unit' => sprintf("%04d", $bid[0]),
                    'nama_unit' => $bid[1],
                    'id_puskesmas' => Auth::user()->id_puskesmas,
                    'nama_puskesmas' => Auth::user()->nama_puskesmas,
                    'tahun' => Input::get('tahun'),
                    'keterangan' => strtoupper(Input::get('ket')),
                    'no_bukti' => Input::get('nmr_bukti'),
                    'tgl_bukti' => Input::get('tgl_bukti'),
                    'jenis_surat' => strtoupper(Input::get('jenis_surat')),
                    'no_surat' => Input::get('nmr_surat'),
                    'tgl_surat' => Input::get('tgl_surat'),
                    'no_acara' => Input::get('nmr_acara'),
                    'tgl_acara' => Input::get('tgl_acara'),
                    'status' => 0
                )
            );
            $df = DB::table('aset_masukdtl')->where('nomor', Input::get('nmr'))->get();
            for ($i = 0; $i < count($df); $i++) {
                $aset = DB::table('aset_data')->where('id_aset', $df[$i]->id_aset)->first();
                DB::table('aset_data')->where('id_aset', $df[$i]->id_aset)->update(array('valid' => '0'));
            }
            DB::table('aset_masukdtl')->where('nomor', Input::get('nmr'))->delete();
            //DB::table('tbstock')->where('nomor', Input::get('nmr'))->delete();
            $kode = Input::get('nmr');
        }
        for ($i = 0; $i < count($brg); $i++) {
            $aset = DB::table('aset_data')->where('id', $brg[$i])->first();
            DB::table('aset_data')->where('id', $aset->id)->update(
                array(
                    'valid' => '1',
                    'id_ruangan' => sprintf("%04d", $bid[0]),
                    'nama_ruangan' => $bid[1]
                )
            );
            DB::table('aset_masukdtl')->insert(
                array(
                    'nomor' => $kode,
                    'id_aset' => $aset->id,
                    'jumlah' => $aset->jumlah,
                    'harga' => $aset->h_satuan
                )
            );
        }
        return Redirect::route('aset-masuk')
            ->with('register_success', 'Master Penerimaan ASET berhasil di-update');
    }

    public function store_asetkeluar()
    {
        $cek = Input::get('edit');
        if (Input::get('tujuan') == 'intern') {
            $unit = explode('-', Input::get('unit'));
        } else {
            $unit = explode('-', Input::get('extern'));
        }
        if (Input::get('barang') != null) {
            //$tipe = Input::get('tipe');
            $brg = Input::get('barang');
            $jum = Input::get('jumlah');
            $sat = Input::get('satuan');
            $ket = Input::get('keterangan');
            if ($cek == '0') {
                $maks = DB::table('aset_keluar')->where('nomor', 'like', 'AL' . sprintf("%03d", Auth::user()->id_puskesmas) . '-' . date('ym') . '%')->max('nomor');
                if ($maks == '') {
                    $baru = 'AL' . sprintf("%03d", Auth::user()->id_puskesmas) . '-' . date('ym') . sprintf("%04d", 1);
                } else {
                    $baru = substr($maks, 0, 10) . sprintf("%04d", substr($maks, 10) + 1);
                }
                DB::table('aset_keluar')->insert(
                    array(
                        'nomor' => $baru,
                        'tanggal' => Input::get('tgl'),
                        'id_puskesmas' => Auth::user()->id_puskesmas,
                        'nama_puskesmas' => Auth::user()->nama_puskesmas,
                        'id_unit' => $unit[0],
                        'nama_unit' => $unit[1],
                        'tujuan' => Input::get('tujuan'),
                        'keterangan' => strtoupper(Input::get('ket'))
                    )
                );
                $kode = $baru;
            } else {
                DB::table('aset_keluar')->where('nomor', Input::get('nmr'))->update(
                    array(
                        'tanggal' => Input::get('tgl'),
                        'id_unit' => $unit[0],
                        'nama_unit' => $unit[1],
                        'tujuan' => Input::get('tujuan'),
                        'keterangan' => strtoupper(Input::get('ket'))
                    )
                );
                $klr = DB::table('aset_keluardtl')->where('nomor', Input::get('nmr'))->get();
                for ($i = 0; $i < count($klr); $i++) {
                    $tmp = DB::table('aset_data')->where('id', $klr[$i]->inv_asal)->first();
                    DB::table('aset_data')->where('id', $tmp->id)->update(array('jumlah' => $tmp->jumlah + $klr[$i]->jumlah));
                }
                DB::table('aset_keluardtl')->where('nomor', Input::get('nmr'))->delete();
                $kode = Input::get('nmr');
            }
            for ($i = 0; $i < count($brg); $i++) {
                $a = explode('-', $brg[$i]);
                $inv = DB::table('aset_data')->where('id', $a[0])->first();
                DB::table('aset_data')->where('id', $inv->id)->update(array('jumlah' => $inv->jumlah - $jum[$i]));
                $newid = $inv->id;
                $k = DB::table('aset_data')->insertGetId(
                    array(
                        'id_aset' => $inv->id_aset,
                        'id_ruangan' => $unit[0],
                        'nama_ruangan' => $unit[1],
                        'id_puskesmas' => $inv->id_puskesmas,
                        'nama_puskesmas' => $inv->nama_puskesmas,
                        'kode_bidang' => $inv->kode_bidang,
                        'desc_bidang' => $inv->desc_bidang,
                        'kode_perwali' => $inv->kode_perwali,
                        'no_register' => $inv->no_register,
                        'nama' => $inv->nama,
                        'jumlah' => $jum[$i],
                        'satuan' => $inv->satuan,
                        'h_satuan' => $inv->h_satuan,
                        'ppn' => $inv->ppn,
                        'tipe_aset' => $inv->tipe_aset,
                        'valid' => '1'
                    )
                );
                DB::table('aset_keluardtl')->insert(
                    array(
                        'nomor' => $kode,
                        'id_inventori' => $k,
                        'inv_asal' => $newid,
                        'jumlah' => $jum[$i],
                        'harga' => $inv->h_satuan,
                        'keterangan' => $ket[$i],
                        'tipe' => 'aset'
                    )
                );
                //DB::table('aset_data')->where('id', $inv->id)->update(array('jumlah' => $inv->jumlah - $jum[$i]));
            }
            return Redirect::route('aset-keluar')
                ->with('register_success', 'Master Alokasi Barang berhasil di-update');
        } else {
            return Redirect::route('aset-keluar')
                ->with('register_failed', 'Perubahan data GAGAL');
        }
    }

    //puskesmas
    public function store_pkmalokasi()
    {
        $cek = Input::get('edit');
        if (Input::get('tujuan') == 'intern') {
            $unit = explode('-', Input::get('unit'));
        } else {
            $unit = explode('-', Input::get('extern'));
        }
        //if (Input::get('barang') != null) {
        $tipe = Input::get('tipe');
        $brg = Input::get('barang');
        $jum = Input::get('jumlah');
        $sat = Input::get('satuan');
        $ket = Input::get('keterangan');
        if ($cek == '0') {
            $maks = DB::table('pkm_alokasi')->where('nomor', 'like', 'ALO' . sprintf("%03d", Auth::user()->id_puskesmas) . '-' . date('ym') . '%')->max('nomor');
            if ($maks == '') {
                $kode = 'ALO' . sprintf("%03d", Auth::user()->id_puskesmas) . '-' . date('ym') . sprintf("%04d", 1);
            } else {
                $kode = substr($maks, 0, 11) . sprintf("%04d", substr($maks, 11) + 1);
            }
            DB::table('pkm_alokasi')->insert(
                array(
                    'nomor' => $kode,
                    'tanggal' => Input::get('tgl'),
                    'id_puskesmas' => Auth::user()->id_puskesmas,
                    'nama_puskesmas' => Auth::user()->nama_puskesmas,
                    'id_unit' => $unit[0],
                    'nama_unit' => $unit[1],
                    'tujuan' => Input::get('tujuan'),
                    'keterangan' => strtoupper(Input::get('ket'))
                )
            );
        } else {
            DB::table('pkm_alokasi')->where('nomor', Input::get('nmr'))->update(
                array(
                    'tanggal' => Input::get('tgl'),
                    'id_unit' => $unit[0],
                    'nama_unit' => $unit[1],
                    'tujuan' => Input::get('tujuan'),
                    'keterangan' => strtoupper(Input::get('ket'))
                )
            );
            $klr = DB::table('pkm_alokasidtl')->where('nomor', Input::get('nmr'))->get();
            for ($i = 0; $i < count($klr); $i++) {
                if ($klr[$i]->tipe == 'hp') {
                    $tmp = DB::table('pkm_inventori')->where('id', $klr[$i]->id_inventori)->first();
                    DB::table('pkm_inventori')->where('id', $tmp->id)->update(array('stok' => $tmp->stok + $klr[$i]->jumlah));
                    //update urutan
                    $upd = DB::table('pkm_inventori')->where('id_barang', $tmp->id_barang)->where('id_puskesmas', Auth::user()->id_puskesmas)->orderBy('tanggal', 'asc')->get();
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
                } else {
                    $tmp = DB::table('aset_data')->where('id', $klr[$i]->id_inventori)->first();
                    $tm = DB::table('aset_data')->where('id_aset', $tmp->id_aset)->where('id_ruangan', '')->first();
                    DB::table('aset_data')->where('id_aset', $tmp->id_aset)->where('id_ruangan', '')
                        ->update(array('jumlah' => $tm->jumlah + $klr[$i]->jumlah));
                    DB::table('aset_data')->where('id', $klr[$i]->id_inventori)->delete();
                }
            }
            DB::table('pkm_alokasidtl')->where('nomor', Input::get('nmr'))->delete();
            $kode = Input::get('nmr');
        }
        $data = '';
        for ($i = 0; $i < count($brg); $i++) {
            $a = explode('-', $brg[$i]);
            if ($tipe[$i] == 'hp') {
                $temp = $jum[$i];
                $inv = DB::table('pkm_inventori')->where('id_barang', $a[0])->where('id_puskesmas', Auth::user()->id_puskesmas)->where('urutan', '<>', '')->orderBy('urutan', 'asc')->get();
                for ($j = 0; $j < count($inv); $j++) {
                    if ($temp > 0) {
                        if ($temp < $inv[$j]->stok) {
                            $qty = $temp;
                            $temp = 0;
                        } else {
                            $qty = $inv[$j]->stok;
                            $temp -= $inv[$j]->stok;
                        }
                        DB::table('pkm_alokasidtl')->insert(
                            array(
                                'nomor' => $kode,
                                'id_inventori' => $inv[$j]->id,
                                'jumlah' => $qty,
                                'harga' => $inv[$j]->harga,
                                'keterangan' => $ket[$i],
                                'tipe' => 'hp'
                            )
                        );
                        if (($inv[$j]->stok - $qty) == 0) {
                            $upd = DB::table('pkm_inventori')->where('id_barang', $a[0])->where('urutan', '<>', '')->get();
                            if (count($upd) == 1) {
                                DB::table('pkm_inventori')->where('id', $inv[$j]->id)->update(array('urutan' => '1'));
                            } else {
                                for ($k = 0; $k < count($upd); $k++) {
                                    DB::table('pkm_inventori')->where('id', $upd[$k]->id)->update(array('urutan' => ($upd[$k]->urutan - 1)));
                                }
                                DB::table('pkm_inventori')->where('id', $inv[$j]->id)->update(array('urutan' => ''));
                            }
                            DB::table('pkm_inventori')->where('id', $inv[$j]->id)->update(array('stok' => ($inv[$j]->stok - $qty)));
                        } else {
                            DB::table('pkm_inventori')->where('id', $inv[$j]->id)->update(array('stok' => ($inv[$j]->stok - $qty)));
                        }
                    }
                }
            } else {
                if (Input::get('tujuan') == 'intern') {
                    $inv = DB::table('aset_data')->where('id_aset', $a[0])->where('id_ruangan', '')->first();
                    $newid = DB::table('aset_data')->insertGetId(
                        array(
                            'id_aset' => $inv->id_aset,
                            'id_ruangan' => $unit[0],
                            'nama_ruangan' => $unit[1],
                            'id_puskesmas' => $inv->id_puskesmas,
                            'nama_puskesmas' => $inv->nama_puskesmas,
                            'kode_bidang' => $inv->kode_bidang,
                            'desc_bidang' => $inv->desc_bidang,
                            'kode_perwali' => $inv->kode_perwali,
                            'no_register' => $inv->no_register,
                            'nama' => $inv->nama,
                            'jumlah' => $jum[$i],
                            'satuan' => $inv->satuan,
                            'h_satuan' => $inv->h_satuan,
                            'ppn' => $inv->ppn,
                            'tipe_aset' => $inv->tipe_aset,
                            'valid' => $inv->valid
                        )
                    );
                    DB::table('aset_data')->where('id', $inv->id)->update(array('jumlah' => $inv->jumlah - $jum[$i]));
                    DB::table('pkm_alokasidtl')->insert(
                        array(
                            'nomor' => $kode,
                            'id_inventori' => $newid,
                            'jumlah' => $jum[$i],
                            'harga' => $inv->h_satuan,
                            'keterangan' => $ket[$i],
                            'tipe' => 'aset'
                        )
                    );
                } else {
                    $inv = DB::table('aset_data')->where('id_aset', $a[0])->where('id_ruangan', '')->first();
                    $dtl = DB::table('aset_' . $inv->tipe_aset)->first();
                    $id = DB::table('aset_data')->where('id_aset', 'like', substr($inv->id_aset, 0, 1) . sprintf("%03d", $unit[0]) . '%')->max('id_aset');
                    //buat aset baru di pkm
                    if ($id == '') {
                        $baru = substr($inv->id_aset, 0, 1) . sprintf("%03d", Auth::user()->id_puskesmas) . sprintf("%04d", 1);
                    } else {
                        $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                    }
                    $newid = DB::table('aset_data')->insertGetId(
                        array(
                            'id_aset' => $baru,
                            'id_puskesmas' => sprintf("%03d", $unit[0]),
                            'nama_puskesmas' => $unit[1],
                            'kode_bidang' => $inv->kode_bidang,
                            'desc_bidang' => $inv->desc_bidang,
                            'kode_perwali' => $inv->kode_perwali,
                            'no_register' => $inv->no_register,
                            'nama' => $inv->nama,
                            'jumlah' => $jum[$i],
                            'satuan' => $inv->satuan,
                            'h_satuan' => $inv->h_satuan,
                            'ppn' => $inv->ppn,
                            'tipe_aset' => $inv->tipe_aset,
                            'valid' => $inv->valid
                        )
                    );
                    $dtl->id_aset = $baru;
                    DB::table('aset_' . $inv->tipe_aset)->insert((array)$dtl);
                    DB::table('aset_data')->where('id', $inv->id)->update(array('jumlah' => $inv->jumlah - $jum[$i]));
                    DB::table('pkm_alokasidtl')->insert(
                        array(
                            'nomor' => $kode,
                            'id_inventori' => $newid,
                            'inv_asal' => $inv->id,
                            'jumlah' => $jum[$i],
                            'harga' => $inv->h_satuan,
                            'keterangan' => $ket[$i],
                            'tipe' => 'aset'
                        )
                    );
                }
                $data = 'mix';
            }
            DB::table('pkm_alokasi')->where('nomor', $kode)->update(array('data' => $data));
        }
        //sync aset to pkm
        if (Auth::user()->id_puskesmas == '0999' && $data == 'mix' && $cek == 0 && Input::get('tujuan') != 'intern') {
            $maks = DB::table('pkm_masuk')->where('nomor', 'like', 'MSK' . sprintf("%03d", $unit[0]) . '-' . date('ym') . '%')->max('nomor');
            if ($maks == '') {
                $maks = 'MSK' . sprintf("%03d", $unit[0]) . '-' . date('ym') . sprintf("%04d", 1);
            } else {
                $maks = substr($maks, 0, 11) . sprintf("%04d", substr($maks, 11) + 1);
            }
            $code = $maks;
            DB::table('pkm_masuk')->insert(
                array(
                    'nomor' => $maks,
                    'tanggal' => Input::get('tgl'),
                    'jenis' => 'transfer masuk',
                    'barang' => 'aset',
                    'id_supplier' => sprintf("%04d", Auth::user()->id_puskesmas),
                    'nama_supplier' => Auth::user()->nama_puskesmas,
                    /*'id_sumber' => '',
                    'nama_sumber' => '',
                    'id_unit' => '',
                    'nama_unit' => '',*/
                    'id_puskesmas' => sprintf("%04d", $unit[0]),
                    'nama_puskesmas' => $unit[1],
                    'tahun' => date('Y'),
                    'keterangan' => '',
                    'no_bukti' => $kode,
                    'status' => 0
                )
            );
            $aa = DB::table('pkm_alokasidtl')->where('nomor', $kode)->where('tipe', 'aset')->get();
            foreach ($aa as $row) {
                DB::table('pkm_masukdtl')->insert(
                    array(
                        'nomor' => $code,
                        'id_inventori' => $row->id_inventori,
                        'jumlah' => $row->jumlah,
                        'harga' => $row->harga,
                        'tipe' => 'aset',
                    )
                );
            }
            //}
            
        }
        //sync habis pakai to pkm
        //input data tabel pkm barang
            //$k_pkm = (int)$pkm[0];
            if ($unit[0] <= 62 && Input::get('tujuan') == 'ekstern' && $cek == 0) {
                $maks = DB::table('pkm_masuk')->where('nomor', 'like', 'MSK' . sprintf("%03d", $unit[0]) . '-' . date('ym') . '%')->max('nomor');
                if ($maks == '') {
                    $nmr = 'MSK' . sprintf("%03d", $unit[0]) . '-' . date('ym') . sprintf("%04d", 1);
                } else {
                    $nmr = substr($maks, 0, 11) . sprintf("%04d", substr($maks, 11) + 1);
                }
                echo $nmr;
                DB::table('pkm_masuk')->insert(
                    array(
                        'nomor' => $nmr,
                        'tanggal' => Input::get('tgl'),
                        'jenis' => 'transfer masuk',
                        'barang' => 'hp',
                        'id_puskesmas' => sprintf("%04d", $unit[0]),
                        'nama_puskesmas' => $unit[1],
                        'id_supplier' => Auth::user()->id_puskesmas,
                        'nama_Supplier' => Auth::user()->nama_puskesmas,
                        'id_sumber' => sprintf("%04d", 1),
                        'nama_sumber' => 'APBD',
                        'no_bukti'=>$kode,
                        'tahun' => date('Y'),
                        //'keterangan' => strtoupper(Input::get('ket')),
                        'status' => 0
                    )
                );
                $hp=DB::table('pkm_alokasidtl')->where('nomor',$kode)->where('tipe','hp')->get();
                for ($i = 0; $i < count($hp); $i++) {
                    $hpdtl=DB::table('pkm_inventori')->where('id',$hp[$i]->id_inventori)->first();
                    $data = DB::table('pkm_inventori')->where('id_barang', $hpdtl->id_barang)->where('id_puskesmas', sprintf("%04d", $unit[0]))->where('id_supplier', Auth::user()->id_puskesmas)->first();
                    if (count($data) == 0) {
                        //$jns = DB::table('tbbarang')->where('cKode', $a[0])->select('cJenis')->first();
                        $id = DB::table('pkm_inventori')->insertGetId(
                            array(
                                'id_barang' =>$hpdtl->id_barang,
                                'nama_barang' => $hpdtl->nama_barang,
                                'id_satuan' => $hpdtl->id_satuan,
                                'nama_satuan' => $hpdtl->nama_satuan,
                                'id_puskesmas' => sprintf("%04d", $unit[0]),
                                'nama_puskesmas' => $unit[1],
                                'id_supplier' => Auth::user()->id_puskesmas,
                                'nama_supplier' => Auth::user()->nama_puskesmas,
                                'nama_sumber' => $hpdtl->nama_sumber,
                                'jenis' => $hpdtl->jenis,
                                'stok' => $hp[$i]->jumlah,
                                'harga' => $hp[$i]->harga,
                                'tanggal' => Input::get('tgl'),
                                'urutan' => 1,
                            )
                        );
                        DB::table('pkm_masukdtl')->insert(
                            array(
                                'nomor' => $nmr,
                                'id_inventori' => $id,
                                'jumlah' => $hp[$i]->jumlah,
                                'harga' => $hp[$i]->harga,
                                'tipe'=>'hp'
                                //'keterangan' => $ket[$i]
                            )
                        );
                    } else {
                        DB::table('pkm_masukdtl')->insert(
                            array(
                                'nomor' => $nmr,
                                'id_inventori' => $id,
                                'jumlah' => $hp[$i]->jumlah,
                                'harga' => $hp[$i]->harga,
                                'tipe'=>'hp'
                                //'keterangan' => $ket[$i]
                            )
                        );
                        DB::table('pkm_inventori')->where('id', $data->id)->update(array('harga' => $hp[$i]->harga, 'stok' => $data->stok + $hp[$i]->jumlah, 'tanggal' => Input::get('tgl')));
                    }
                    //update pengurutan barang tabel inventori
                    $upd = DB::table('pkm_inventori')->where('id_barang', $a[0])->where('id_puskesmas', sprintf("%04d", $unit[0]))->orderBy('tanggal', 'asc')->get();
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
            }
            return Redirect::route('pkm-alokasi')
                ->with('register_success', 'Master Alokasi Barang berhasil di-update');
    }

    public function store_pkmmasuk()
    {
        $cek = Input::get('edit');
        $sup = explode('-', Input::get('supplier'));
        $sum = explode('-', Input::get('sumber'));
        $bid = explode('-', Input::get('bidang'));
        $brg = Input::get('barang');
        $jum = Input::get('jumlah');
        $sat = Input::get('satuan');
        $h_sat = Input::get('h_satuan');
        $ppn = Input::get('ppn');
        if ($cek == '0') {
            $maks = DB::table('pkm_masuk')->where('nomor', 'like', 'MSK' . sprintf("%03d", Auth::user()->id_puskesmas) . '-' . date('ym') . '%')->max('nomor');
            if ($maks == '') {
                $maks = 'MSK' . sprintf("%03d", Auth::user()->id_puskesmas) . '-' . date('ym') . sprintf("%04d", 1);
            } else {
                $maks = substr($maks, 0, 11) . sprintf("%04d", substr($maks, 11) + 1);
            }
            DB::table('pkm_masuk')->insert(
                array(
                    'nomor' => $maks,
                    'tanggal' => Input::get('tgl'),
                    'jenis' => Input::get('trm'),
                    'barang' => Input::get('am'),
                    'id_supplier' => sprintf("%04d", $sup[0]),
                    'nama_supplier' => $sup[1],
                    'id_sumber' => sprintf("%04d", $sum[0]),
                    'nama_sumber' => $sum[1],
                    'id_unit' => sprintf("%04d", $bid[0]),
                    'nama_unit' => $bid[1],
                    'id_puskesmas' => Auth::user()->id_puskesmas,
                    'nama_puskesmas' => Auth::user()->nama_puskesmas,
                    'tahun' => Input::get('tahun'),
                    'keterangan' => strtoupper(Input::get('ket')),
                    'no_bukti' => Input::get('nmr_bukti'),
                    'tgl_bukti' => Input::get('tgl_bukti'),
                    'jenis_surat' => strtoupper(Input::get('jenis_surat')),
                    'no_surat' => Input::get('nmr_surat'),
                    'tgl_surat' => Input::get('tgl_surat'),
                    'no_acara' => Input::get('nmr_acara'),
                    'tgl_acara' => Input::get('tgl_acara'),
                    'status' => 0
                )
            );
            $kode = $maks;
        } else {
            DB::table('pkm_masuk')->where('nomor', Input::get('nmr'))->update(
                array(
                    'tanggal' => Input::get('tgl'),
                    'jenis' => Input::get('trm'),
                    'barang' => Input::get('am'),
                    'id_supplier' => sprintf("%04d", $sup[0]),
                    'nama_supplier' => $sup[1],
                    'id_sumber' => sprintf("%04d", $sum[0]),
                    'nama_sumber' => $sum[1],
                    'id_unit' => sprintf("%04d", $bid[0]),
                    'nama_unit' => $bid[1],
                    'id_puskesmas' => Auth::user()->id_puskesmas,
                    'nama_puskesmas' => Auth::user()->nama_puskesmas,
                    'tahun' => Input::get('tahun'),
                    'keterangan' => strtoupper(Input::get('ket')),
                    'no_bukti' => Input::get('nmr_bukti'),
                    'tgl_bukti' => Input::get('tgl_bukti'),
                    'jenis_surat' => strtoupper(Input::get('jenis_surat')),
                    'no_surat' => Input::get('nmr_surat'),
                    'tgl_surat' => Input::get('tgl_surat'),
                    'no_acara' => Input::get('nmr_acara'),
                    'tgl_acara' => Input::get('tgl_acara'),
                    'status' => 0
                )
            );
            if (Input::get('am') == 'aset') {
                $as = DB::table('pkm_masukdtl')->where('nomor', Input::get('nmr'))->get();
                foreach ($as as $row) {
                    DB::table('aset_data')->where('id', $row->id_inventori)->update(array('valid' => '0'));
                }
            } else {
                $as = DB::table('pkm_masukdtl')->where('nomor', Input::get('nmr'))->get();
                foreach ($as as $row) {
                    $st = DB::table('pkm_inventori')->where('id', $row->id_inventori)->first();
                    DB::table('pkm_inventori')->where('id', $row->id_inventori)->update(array('stok' => ($st->stok - $row->jumlah)));
                }
            }
            DB::table('pkm_masukdtl')->where('nomor', Input::get('nmr'))->delete();
            //DB::table('tbstock')->where('nomor', Input::get('nmr'))->delete();
            $kode = Input::get('nmr');
        }
        for ($i = 0; $i < count($brg); $i++) {
            if (Input::get('am') == 'aset') {
                $aset = DB::table('aset_data')->where('id', $brg[$i])->first();
                DB::table('aset_data')->where('id', $aset->id)->update(array('valid' => '1'));
                DB::table('pkm_masukdtl')->insert(
                    array(
                        'nomor' => $kode,
                        'id_inventori' => $aset->id,
                        'jumlah' => $aset->jumlah,
                        'harga' => $aset->h_satuan,
                        'tipe' => 'aset',
                    )
                );
            } else {
                $a = explode('-', $brg[$i]);
                $b = explode('-', $sat[$i]);
                $jns = DB::table('pkm_barang')->where('cKode', $a[0])->first();
                $data = DB::table('pkm_inventori')->where('id_barang', $a[0])->where('id_puskesmas', Auth::user()->id_puskesmas)->where('id_supplier', $sup[0])->first();
                if (count($data) == 0) {
                    $id = DB::table('pkm_inventori')->insertGetId(
                        array(
                            'id_barang' => sprintf("%06d", $a[0]),
                            'nama_barang' => $a[1],
                            'id_satuan' => sprintf("%04d", $b[0]),
                            'nama_satuan' => $b[1],
                            'id_puskesmas' => Auth::user()->id_puskesmas,
                            'nama_puskesmas' => Auth::user()->nama_puskesmas,
                            'id_supplier' => $sup[0],
                            'nama_supplier' => $sup[1],
                            'nama_sumber' => $sum[1],
                            'jenis' => $jns->cJenis,
                            'stok' => $jum[$i],
                            'harga' => $h_sat[$i],
                            'ppn' => $ppn[$i],
                            'tanggal' => Input::get('tgl'),
                            'urutan' => 1,
                        )
                    );
                    $upd = DB::table('pkm_inventori')->where('id_barang', sprintf("%06d", $a[0]))->orderBy('tanggal', 'asc')->get();
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
                    DB::table('pkm_masukdtl')->insert(
                        array(
                            'nomor' => $kode,
                            'id_inventori' => $id,
                            'jumlah' => $jum[$i],
                            'harga' => $h_sat[$i],
                            'tipe' => 'hp',
                        )
                    );
                } else {
                    DB::table('pkm_masukdtl')->insert(
                        array(
                            'nomor' => $kode,
                            'id_inventori' => $data->id,
                            'jumlah' => $jum[$i],
                            'harga' => $h_sat[$i],
                            'tipe' => 'hp',
                        )
                    );
                    DB::table('pkm_inventori')->where('id', $data->id)->update(array('stok' => ($data->stok + $jum[$i])));
                }
            }
        }
        return Redirect::route('pkm-masuk')
            ->with('register_success', 'Master Penerimaan berhasil di-update');
    }

    public function store_pkmsupplier()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('pkm_supplier')->where('id_puskesmas', Auth::user()->id_puskesmas)->max('kode');
            DB::table('pkm_supplier')->insert(
                array(
                    'kode' => sprintf("%04d", $maks + 1),
                    'nama_supplier' => strtoupper(Input::get('nama')),
                    'alamat_supplier' => strtoupper(Input::get('alamat')),
                    'id_puskesmas' => Auth::user()->id_puskesmas
                )
            );
        } else {
            DB::table('pkm_supplier')
                ->where('id', sprintf("%04d", Input::get('id')))
                ->update(array('nama_supplier' => strtoupper(Input::get('nama')), 'alamat_supplier' => strtoupper(Input::get('alamat'))));
        }

        return Redirect::route('pkm-supplier')
            ->with('register_success', 'Master Supplier berhasil di-update');
        // 2b. jika tidak, kembali ke halaman form registrasi
    }

    public function store_pkmuser()
    {
        if (Input::get('id') == "") {
            DB::table('pkm_login')->insert(
                array(
                    'nama' => strtoupper(Input::get('nama')),
                    'username' => Input::get('uname'),
                    'password' => Hash::make(Input::get('pass')),
                    'id_puskesmas' => Auth::user()->id_puskesmas,
                    'kode' => Auth::user()->kode,
                    'role' => 2
                )
            );
        } else {
            DB::table('pkm_login')
                ->where('id', Input::get('id'))
                ->update(array('nama' => strtoupper(Input::get('nama')), 'username' => Input::get('uname'), 'password' => Hash::make(Input::get('pass'))));
        }

        return Redirect::route('pkm-user')
            ->with('register_success', 'User berhasil di-update');
        // 2b. jika tidak, kembali ke halaman form registrasi
    }

    //Admin DKK

    public function store_unit()
    {
        if (Input::get('id') == "") {
            $maks = DB::table('pkm_unit')->max('id');
            DB::table('pkm_unit')->insert(
                array(
                    'id' => sprintf("%04d", $maks + 1),
                    'nama_unit' => strtoupper(Input::get('nama'))
                )
            );
        } else {
            DB::table('pkm_unit')->where('id', sprintf("%04d", Input::get('id')))->update(array('nama_unit' => strtoupper(Input::get('nama'))));
        }
        return Redirect::route('dkk-unit')
            ->with('register_success', 'Master Unit berhasil di-update');
    }

    public function store_pegawai()
    {
        if (Input::get('id') == "") {
            //$maks = DB::table('pkm_pegawai')->max('id');
			$tt=explode('-',Input::get('unit'));
            DB::table('pkm_pegawai')->insert(
                array(
                    'nama_pegawai' => strtoupper(Input::get('nama')),
                    'nip' => strtoupper(Input::get('nip')),
                    'pangkat' => strtoupper(Input::get('pangkat')),
                    'jabatan' => strtoupper(Input::get('jabatan')),
                    'unit' => strtoupper($tt[1]),
                    'id_unit' => $tt[0],
                    'keterangan' => strtoupper(Input::get('ket'))
                )
            );
        } else {
			$tt=explode('-',Input::get('unit'));
            DB::table('pkm_pegawai')->where('id', Input::get('id'))->update(
                array(
                    'nama_pegawai' => strtoupper(Input::get('nama')),
                    'nip' => strtoupper(Input::get('nip')),
                    'pangkat' => strtoupper(Input::get('pangkat')),
                    'jabatan' => strtoupper(Input::get('jabatan')),
                    'unit' => strtoupper($tt[1]),
                    'id_unit' => $tt[0],
                    'keterangan' => strtoupper(Input::get('ket'))
                )
            );
        }
        return Redirect::route('dkk-pegawai')
            ->with('register_success', 'Master Pegawai berhasil di-update');
    }


}
