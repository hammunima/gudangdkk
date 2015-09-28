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
            $maks = DB::table('tbbarang')->max('cKode');
            DB::table('tbbarang')->insert(
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
                    'nharga' => Input::get('harga'),
                    'ikondisi' => Input::get('kondisi')
                )
            );
        } else {
            DB::table('tbbarang')
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
                        'nharga' => Input::get('harga'),
                        'ikondisi' => Input::get('kondisi')
                    )
                );
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
                    'cNama' => strtoupper(Input::get('nama'))
                )
            );
        } else {
            DB::table('tbsumberanggaran')->where('cKode', sprintf("%04d", Input::get('id')))->update(array('cNama' => strtoupper(Input::get('nama'))));
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
    //puskesmas
    public function store_pkmalokasi()
    {
        $cek = Input::get('edit');
        $unit = explode('-', Input::get('unit'));
        if (Input::get('barang') != null) {
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
                        'keterangan' => strtoupper(Input::get('ket'))
                    )
                );
            } else {
                DB::table('pkm_alokasi')->where('nomor', Input::get('nmr'))->update(
                    array(
                        'tanggal' => Input::get('tgl'),
                        'id_unit' => $unit[0],
                        'nama_unit' => $unit[1],
                        'keterangan' => strtoupper(Input::get('ket'))
                    )
                );
                $klr = DB::table('pkm_alokasidtl')->where('nomor', Input::get('nmr'))->get();
                for ($i = 0; $i < count($klr); $i++) {
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
                }
                DB::table('pkm_alokasidtl')->where('nomor', Input::get('nmr'))->delete();
                $kode = Input::get('nmr');
            }
            for ($i = 0; $i < count($brg); $i++) {
                $a = explode('-', $brg[$i]);
                $b = explode('-', $sat[$i]);
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
                                'keterangan' => $ket[$i]
                            )
                        );
                        if (($inv[$j]->stok - $qty) == 0) {
                            $upd = DB::table('pkm_inventori')->where('cKode', $a[0])->where('urutan', '<>', '')->get();
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
            }
            return Redirect::route('pkm-alokasi')
                ->with('register_success', 'Master Alokasi Barang berhasil di-update');
        } else {
            return Redirect::route('pkm-alokasi')
                ->with('register_failed', 'Perubahan data GAGAL');
        }
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
            DB::table('pkm_masukdtl')->where('nomor', Input::get('nmr'))->delete();
            //DB::table('tbstock')->where('nomor', Input::get('nmr'))->delete();
            $kode = Input::get('nmr');
        }
        for ($i = 0; $i < count($brg); $i++) {
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
                        'stok' => 0,
                        'harga' => $h_sat[$i],
                        'tanggal' => Input::get('tgl'),
                        'urutan' => 1,
                    )
                );
                DB::table('pkm_masukdtl')->insert(
                    array(
                        'nomor' => $kode,
                        'id_inventori' => $id,
                        'jumlah' => $jum[$i],
                        'harga' => $h_sat[$i]
                    )
                );
            } else {
                DB::table('pkm_masukdtl')->insert(
                    array(
                        'nomor' => $kode,
                        'id_inventori' => $data->id,
                        'jumlah' => $jum[$i],
                        'harga' => $h_sat[$i]
                    )
                );
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


}
