<?php

class LinkController extends BaseController
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

    public function login()
    {
        return View::make('login');
    }

    public function penyesuaian_barang()
    {
        $m = array();
        $tabel = Datatable::table()
            ->addColumn('Nomor', 'Tanggal', 'Keterangan', 'Action')
            ->setUrl(route('get-adj-barang'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(3)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        $m['brg'] = DB::table('tbinventori')->get();
        $maks = DB::table('tbadj')->where('cNomor', 'like', 'ADJ-' . date('ym') . '%')->max('cNomor');
        if (strpos($maks, 'ADJ') == false) {
            $m['baru'] = 'ADJ-' . date('ym') . sprintf("%04d", 1);
        } else {
            $m['baru'] = substr($maks, 0, 8) . sprintf("%04d", substr($maks, 8) + 1);
        }
        return View::make('sesuai_barang', compact('tabel'), compact('m'));
    }

    public function pengeluaran_barang()
    {
        $m = array();
        $tabel = Datatable::table()
            ->addColumn('Nomor', 'Tanggal', 'Bidang', 'Puskesmas', 'Action')
            ->setUrl(route('get-klr-barang'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(4))),
                    'order' => array(array(0,'desc'))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        $m['brg'] = DB::table('tbstock')->groupBy('cKode')->select(DB::raw('*, sum(nQtyReal) as sum'))->get();
        $maks = DB::table('tbkeluar')->where('cNomor', 'like', 'KLR-' . date('ym') . '%')->max('cNomor');
        if ($maks == '') {
            $m['baru'] = 'KLR-' . date('ym') . sprintf("%04d", 1);
        } else {
            $m['baru'] = substr($maks, 0, 8) . sprintf("%04d", substr($maks, 8) + 1);
        }
        $m['pkm'] = DB::table('tbpuskesmas')->get();
        $m['bidang'] = DB::table('tbbidang')->get();
        $m['brg'] = DB::table('tbbarang')->where('cNama', '<>', '')->get();
        return View::make('keluar_barang', compact('tabel'), compact('m'));
    }

    public function penerimaan_barang()
    {
        $m = array();
        $tabel = Datatable::table()
            ->addColumn('Nomor', 'Tanggal', 'Supplier', 'Sumber', 'Bidang', 'Action')
            ->setUrl(route('get-trm-barang'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(5))),
                    'order' => array(array(0,'desc'))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        $m['brg'] = DB::table('tbbarang')->get();
        $maks = DB::table('tbterima')->where('cNomor', 'like', 'TRM-' . date('ym') . '%')->max('cNomor');
        if ($maks == '') {
            $m['baru'] = 'TRM-' . date('ym') . sprintf("%04d", 1);
        } else {
            $m['baru'] = substr($maks, 0, 8) . sprintf("%04d", substr($maks, 8) + 1);
        }
        //$m['baru']=$maks;
        $m['sup'] = DB::table('tbsupplier')->get();
        $m['sumber'] = DB::table('tbsumberanggaran')->get();
        $m['bidang'] = DB::table('tbbidang')->get();
        return View::make('terima_barang', compact('tabel'), compact('m'));
    }

    public function m_stok()
    {
        $tabel = Datatable::table()
            //->addColumn('Nama Barang', 'Satuan', 'Harga', 'Stok')
            ->addColumn('Kode Barang', 'Nama Barang', 'Supplier', 'Tanggal Masuk', 'Stok')
            ->setUrl(route('get-stok'))
            ->setClass('table datatable')
            ->noScript();
        $jenis = DB::table('tbjenis')->get();
        return View::make('m_stok', compact('tabel'), compact('jenis'));
    }

    public function m_barang()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode','Nama Barang', 'Jenis', 'Satuan', 'Action')
            ->setUrl(route('get-barang'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(4)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        $m = array();
        $m['jenis'] = DB::table('tbjenis')->get();
        $m['merk'] = DB::table('tbmerk')->get();
        $m['tipe'] = DB::table('tbtipe')->get();
        $m['satuan'] = DB::table('tbsatuan')->get();
        return View::make('m_barang', compact('tabel'), compact('m'));
    }

    public function m_puskesmas()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode', 'Nama Puskesmas', 'Action')
            ->setUrl(URL::to('tabel/puskesmas'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(2)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        return View::make('m_puskesmas', compact('tabel'));
    }

    public function m_bidang()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode', 'Nama Bidang', 'Action')
            ->setUrl(URL::to('tabel/bidang'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(2)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        return View::make('m_bidang', compact('tabel'));
    }

    public function m_supplier()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode', 'Nama Supplier', 'Action')
            ->setUrl(URL::to('tabel/supplier'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(2)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        return View::make('m_supplier', compact('tabel'));
    }

    public function m_sumberanggaran()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode', 'Sumber Anggaran', 'Action')
            ->setUrl(URL::to('tabel/sumberanggaran'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(2)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        return View::make('m_sumberanggaran', compact('tabel'));
    }

    public function m_jenis()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode', 'Nama Jenis', 'Action')
            ->setUrl(URL::to('tabel/jenis'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(2)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        return View::make('m_jenis', compact('tabel'));
    }

    public function m_satuan()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode', 'Nama Satuan', 'Action')
            ->setUrl(URL::to('tabel/satuan'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(2)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        return View::make('m_satuan', compact('tabel'));
    }

    public function m_tipe()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode', 'Nama Tipe', 'Action')
            ->setUrl(URL::to('tabel/tipe'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(2)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        return View::make('m_tipe', compact('tabel'));
    }

    public function m_merk()
    {
        $tabel = Datatable::table()
            ->addColumn('Kode', 'Nama Merk', 'Action')
            ->setUrl(URL::to('tabel/merk'))
            ->setOptions(
                array(
                    'aoColumnDefs' => array(array('bSortable' => false, 'aTargets' => array(2)))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        return View::make('m_merk', compact('tabel'));
    }


    //Puskesmas
    public function alokasi_barang()
    {
        $m = array();
        $tabel = Datatable::table()
            ->addColumn('Nomor', 'Tanggal', 'Unit Alokasi', 'Action')
            ->setUrl(route('get-pkm-alo'))
            ->setOptions(
                array(
                    'order' => array(array(0,'desc'))
                )
            )
            ->setClass('table datatable')
            ->noScript();
        $m['inventori'] = DB::table('pkm_inventori')->get();
        $maks = DB::table('pkm_alokasi')->max('nomor');
        if ($maks == '') {
            $m['baru'] = 'ALO-' . date('ym') . sprintf("%04d", 1);
        } else {
            $m['baru'] = substr($maks, 0, 8) . sprintf("%04d", substr($maks, 8) + 1);
        }
        $m['unit'] = DB::table('pkm_unit')->get();
        return View::make('pkm_alokasi', compact('tabel'), compact('m'));
    }
}
