<?php
//
class GenerateController extends BaseController
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

    public function showWelcome()
    {
        return View::make('hello');
    }

    public function help()
    {
        $tmp = DB::table('tmp_seleksi')->get();
        foreach ($tmp as $row) {
            DB::table('tmp_seleksi')->where('ID', $row->ID)->update(array('HP' => sprintf("%06d", $row->HP), 'ASET' => sprintf("%06d", $row->ASET)));
        }
    }

    public function help2()
    {
        //migrasi tbkeluar
		set_time_limit(900);
        /*$tmp = DB::table('tbkeluar')->where('dTanggal', '>=', '2015-01-01')->where('lposted','1')->get();
        foreach ($tmp as $row) {
            if ($row->cKdBidang == 0) {
                $tujuan = 'extern';
                $idu = $row->cKdPuskesmas;
                $namau = $row->cPuskesmas;
            } else {
                $tujuan = 'intern';
                $idu = $row->cKdBidang;
                $namau = $row->cBidang;
            }
            DB::table('pkm_alokasi')->insert(
                array(
                    'nomor' => 'ALO999-' . substr($row->cNomor, 4),
                    'tanggal' => date('Y-m-d', strtotime($row->dTanggal)),
                    'id_puskesmas' => '0999',
                    'nama_puskesmas' => 'Dinas Kesehatan Kota Surabaya',
                    'id_unit' => $idu,
                    'nama_unit' => $namau,
                    'tujuan' => $tujuan,
                    'keterangan' => $row->cKeterangan,
                    'data' => '',
                )
            );
        }
		echo count($tmp);*/
        //migasi tbkeluardtl        
        $tmp = DB::table('tbkeluardtl')->orderBy('cNomor', 'asc')->get();
        $n = 0;
		foreach ($tmp as $row) {
            $cek = DB::table('pkm_alokasi')->where('nomor', 'ALO999-' . substr($row->cNomor, 4))->count();
            if ($cek > 0) {
                $n++;
                $inv = DB::table('pkm_inventori')->where('id_barang', sprintf("%06d", $row->cKode))->where('id_puskesmas', '0999')->where('urutan', '<>', '')->orderBy('urutan', 'asc')->get();
                $temp = $row->nQty;
                if (count($inv) == 0) {
                    $ast = DB::table('tmp_seleksi')->where('ASET', sprintf("%06d", $row->cKode))->get();
                    if (count($ast) > 0) {
                        //echo 'ASET ' . $row->cKode . '<br>';
                    } else {
                        //echo 'Not Found ' . $row->cKode .' '.$row->cNama. ' ' . $row->cNomor . '<br>';
						//$tipe=DB::table('tbbarang')->where('cKode',$row->cKode)->first();
						//echo $tipe->cJenis.'<br>';
                    }
                }
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
                                'nomor' => 'ALO999-' . substr($row->cNomor, 4),
                                'id_inventori' => $inv[$j]->id,
                                'jumlah' => $qty,
                                'harga' => $inv[$j]->harga,
                                'keterangan' => $row->cKeterangan,
                                'tipe' => 'hp'
                            )
                        );
                        if (($inv[$j]->stok - $qty) == 0) {
                            $upd = DB::table('pkm_inventori')->where('id_barang', sprintf("%06d", $row->cKode))->where('urutan', '<>', '')->get();
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
        }
        echo $n;
    }

    public function generate_aset()
    {
        set_time_limit(1500);
        echo 'asdadasd';
        $skip = 0;
        $n = 0;
        $sum = DB::table('aset_temp')->where('KODE_GOLONGAN', '07')->count();
        echo 'jumlah ' . $sum . '<br>';
        while ($n <= $sum) {
            $temp = DB::table('aset_temp')->where('KODE_GOLONGAN', '07')->skip($n)->take(100)->get();
            for ($i = 0; $i < count($temp); $i++) {
                $gol = $temp[$i]->KODE_GOLONGAN;
                if ($temp[$i]->NOID_PKM == '') {
                    $pkm = '999';
                } else {
                    $pkm = sprintf("%03d", $temp[$i]->NOID_PKM);
                }
                switch ($gol) {
                    case '01':
                        $id = DB::table('aset_data')->where('id_aset', 'like', 'A' . sprintf("%03d", $pkm) . '%')->max('id_aset');
                        if ($id == '') {
                            $baru = 'A' . sprintf("%03d", $pkm) . sprintf("%04d", 1);
                        } else {
                            $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                        }
                        DB::table('aset_tanah')->insert(
                            array(
                                'id_aset' => $baru,
                                'luas' => $temp[$i]->LUAS_TANAH,
                                'tahun' => $temp[$i]->TAHUN_PENGADAAN,
                                'alamat' => $temp[$i]->MERK_ALAMAT,
                                'status' => $temp[$i]->STATUS_TANAH,
                                'no_sertifikat' => $temp[$i]->NO_SERTIFIKAT,
                                'tgl_sertifikat' => $temp[$i]->TGL_SERTIFKAT,
                                'nama_sertifikat' => $temp[$i]->ATAS_NAMA_SERTIFIKAT,
                                'fungsi' => $temp[$i]->PENGGUNAAN,
                                'asal' => $temp[$i]->ASAL_USUL_UNIT,
                                'keterangan' => $temp[$i]->KET
                            )
                        );
                        break;
                    case '02':
                        $id = DB::table('aset_data')->where('id_aset', 'like', 'B' . sprintf("%03d", $pkm) . '%')->max('id_aset');
                        if ($id == '') {
                            $baru = 'B' . sprintf("%03d", $pkm) . sprintf("%04d", 1);
                        } else {
                            $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                        }

                        DB::table('aset_mesin')->insert(
                            array(
                                'id_aset' => $baru,
                                'ruangan' => '',
                                'merk' => $temp[$i]->MERK_ALAMAT,
                                'tipe' => $temp[$i]->TIPE,
                                'ukuran' => '',
                                'b_warna' => $temp[$i]->BAHAN,
                                'no_bpkb' => $temp[$i]->NO_BPKB,
                                'no_polisi' => $temp[$i]->NOPOL,
                                'no_rangka' => $temp[$i]->NO_RANGKA_SERI,
                                'no_mesin' => $temp[$i]->NO_MESIN,
                                'warna' => $temp[$i]->WARNA,
                                'cc' => $temp[$i]->CC,
                                'no_stnk' => $temp[$i]->NO_STNK,
                                'tgl_stnk' => $temp[$i]->TGL_STNK,
                                'bbm' => $temp[$i]->BAHAN_BAKAR,
                                't_pengadaan' => $temp[$i]->TAHUN_PENGADAAN,
                                't_perakitan' => $temp[$i]->TAHUN_PERAKITAN,
                                'asal' => $temp[$i]->ASAL_USUL_UNIT,
                                'nama_pj' => '',
                                'jabatan_pj' => '',
                                'kondisi' => $temp[$i]->KB,
                                'keterangan' => $temp[$i]->KET
                            )
                        );
                        break;
                    case '03':
                        $id = DB::table('aset_data')->where('id_aset', 'like', 'C' . sprintf("%03d", $pkm) . '%')->max('id_aset');
                        if ($id == '') {
                            $baru = 'C' . sprintf("%03d", $pkm) . sprintf("%04d", 1);
                        } else {
                            $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                        }
                        DB::table('aset_bangunan')->insert(
                            array(
                                'id_aset' => $baru,
                                'tahun' => $temp[$i]->TAHUN_PENGADAAN,
                                'alamat' => $temp[$i]->MERK_ALAMAT,
                                'tipe' => $temp[$i]->TIPE,
                                'j_bahan' => $temp[$i]->BAHAN,
                                'j_kontruksi' => $temp[$i]->KONSTRUKSI,
                                'l_lantai' => $temp[$i]->LUAS_LANTAI,
                                'l_bangunan' => $temp[$i]->LUAS_BANGUNAN,
                                'jml_lantai' => $temp[$i]->JUMLAH_LANTAI,
                                'fungsi' => $temp[$i]->PENGGUNAAN,
                                'asal' => $temp[$i]->ASAL_USUL_UNIT,
                                'no_reg_Tanah' => $temp[$i]->NO_REGS_INDUK_TANAH,
                                'l_tanah' => $temp[$i]->LUAS_TANAH,
                                's_tanah' => $temp[$i]->STATUS_TANAH,
                                'dok' => $temp[$i]->JENIS_DOKUMEN_TANAH,
                                'no_dok' => $temp[$i]->NO_IMB,
                                'kondisi' => $temp[$i]->KB,
                                'keterangan' => $temp[$i]->KET
                            )
                        );
                        break;
                    case '04':
                        $id = DB::table('aset_data')->where('id_aset', 'like', 'D' . sprintf("%03d", $pkm) . '%')->max('id_aset');
                        if ($id == '') {
                            $baru = 'D' . sprintf("%03d", $pkm) . sprintf("%04d", 1);
                        } else {
                            $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                        }
                        DB::table('aset_jalan')->insert(
                            array(
                                'id_aset' => $baru,
                                'tahun' => $temp[$i]->TAHUN_PENGADAAN,
                                'alamat' => $temp[$i]->MERK_ALAMAT,
                                'tipe' => $temp[$i]->TIPE,
                                'j_bahan' => $temp[$i]->BAHAN,
                                'j_kontruksi' => $temp[$i]->KONSTRUKSI,
                                'panjang' => $temp[$i]->PANJANG_TANAH,
                                'lebar' => $temp[$i]->LEBAR_TANAH,
                                'luas' => $temp[$i]->LUAS_TANAH,
                                'fungsi' => $temp[$i]->PENGGUNAAN,
                                'asal' => $temp[$i]->ASAL_USUL_UNIT,
                                'no_reg_Tanah' => $temp[$i]->NO_REGS_INDUK_TANAH,
                                'l_tanah' => $temp[$i]->LUAS_TANAH,
                                's_tanah' => $temp[$i]->STATUS_TANAH,
                                'dok' => $temp[$i]->JENIS_DOKUMEN_TANAH,
                                'no_dok' => $temp[$i]->NO_IMB,
                                'kondisi' => $temp[$i]->KB,
                                'keterangan' => $temp[$i]->KET
                            )
                        );
                        break;
                    case '05':
                        $id = DB::table('aset_data')->where('id_aset', 'like', 'E' . sprintf("%03d", $pkm) . '%')->max('id_aset');
                        if ($id == '') {
                            $baru = 'E' . sprintf("%03d", $pkm) . sprintf("%04d", 1);
                        } else {
                            $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                        }
                        DB::table('aset_tetaplain')->insert(
                            array(
                                'id_aset' => $baru,
                                'ruangan' => '',
                                'tahun' => $temp[$i]->TAHUN_PENGADAAN,
                                'judul' => '',
                                'pengarang' => '',
                                'pencipta' => '',
                                'daerah' => '',
                                'bahan' => $temp[$i]->BAHAN,
                                'jenis' => $temp[$i]->SUB_KELOMPOK_DESKRIPSI,
                                'ukuran' => '',
                                'fungsi' => $temp[$i]->PENGGUNAAN,
                                'asal' => $temp[$i]->ASAL_USUL_UNIT,
                                'keterangan' => $temp[$i]->KET
                            )
                        );
                        break;
                    case '07':
                        $id = DB::table('aset_data')->where('id_aset', 'like', 'G' . sprintf("%03d", $pkm) . '%')->max('id_aset');
                        if ($id == '') {
                            $baru = 'G' . sprintf("%03d", $pkm) . sprintf("%04d", 1);
                        } else {
                            $baru = substr($id, 0, 4) . sprintf("%04d", substr($id, 4) + 1);
                        }
                        DB::table('aset_lain')->insert(
                            array(
                                'id_aset' => $baru,
                                'ruangan' => '',
                                'tahun' => $temp[$i]->TAHUN_PENGADAAN,
                                'merk' => '',
                                'tipe' => $temp[$i]->SUB_KELOMPOK_DESKRIPSI,
                                'no_seri' => '',
                                'asal' => $temp[$i]->ASAL_USUL_UNIT,
                                'kondisi' => $temp[$i]->KB,
                                'keterangan' => $temp[$i]->KET
                            )
                        );
                        break;
                }
                DB::table('aset_data')->insert(
                    array(
                        'id_aset' => $baru,
                        'id_puskesmas' => $pkm,
                        'nama_puskesmas' => '',
                        'kode_bidang' => $temp[$i]->KODE_SUB_SUB_KELOMPOK,
                        'desc_bidang' => $temp[$i]->SUBSUB_KELOMPOK_DESKRIPSI,
                        'kode_perwali' => $temp[$i]->KODE_SUB_KEL_AT,
                        'no_register' => $temp[$i]->NO_REGISTER,
                        'nama' => $temp[$i]->NAMA_BARANG,
                        'jumlah' => $temp[$i]->JUMLAH_BARANG,
                        'satuan' => $temp[$i]->SATUAN,
                        'h_satuan' => $temp[$i]->HARGA_SATUAN,
                        'ppn' => 0,
                        'tipe_aset' => 'tanah',
                        'valid' => '1'
                    )
                );
            }
            $n += 100;
            echo $n;
        }
    }

    public function pkm_inventori()
    {
        $data = DB::table('pkm_inventori')->get();
        foreach ($data as $row) {
            $jum = DB::table('pkm_masukdtl')->where('id_inventori', $row->id)->sum('jumlah');
            DB::table('pkm_inventori')->where('id', $row->id)->update(array('stok' => $jum));
        }
        $brg = DB::table('pkm_inventori')->distinct('id_barang')->get();
        foreach ($brg as $baris) {
            $upd = DB::table('pkm_inventori')->where('id_barang', $baris->id_barang)->orderBy('tanggal', 'asc')->get();
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
}
