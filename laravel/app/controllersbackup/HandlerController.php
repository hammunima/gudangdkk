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
                return '<a href="#" onclick="del(' . $a[1] . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('cNomor', 'dTanggal', 'cKeterangan')
            ->make();
    }

    public function get_trm_barang()
    {
        return Datatable::query(DB::table('tbterima')->join('tbterimadtl', 'tbterima.cNomor', '=', 'tbterimadtl.cNomor')->groupBy('tbterima.cNomor')->select('tbterima.*', 'cKode', 'cNama'))
            ->showColumns('cNomor', 'dTanggal', 'cSupplier', 'cSumber', 'cBidang')
            ->addColumn('action', function ($model) {
                $a = explode('-', $model->cNomor);
                return '<a href="#" onclick="pop_view(\'' . $model->cNomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;'.
                '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;';
            })
            ->searchColumns('tbterima.cNomor', 'cKode', 'cNama', 'cSumber', 'cBidang', 'cSupplier')
            ->make();
    }

    public function get_klr_barang()
    {
        return Datatable::query(DB::table('tbkeluar')->join('tbkeluardtl', 'tbkeluar.cNomor', '=', 'tbkeluardtl.cNomor')->groupBy('tbkeluar.cNomor')->select('tbkeluar.*', 'cKode', 'cNama'))
            ->showColumns('cNomor', 'dTanggal', 'cBidang', 'cPuskesmas')
            ->addColumn('action', function ($model) {
                $a = explode('-', $model->cNomor);
                return '<a href="#" onclick="pop_view(\'' . $model->cNomor . '\')" title="Lihat Detail"><i class="glyphicon glyphicon-search"></i></a>&nbsp;&nbsp;'.
                '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="' . URL::to("nota/" . $model->cNomor) . '" target="_blank" title="Cetak"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;';  
            })
            ->searchColumns('tbkeluar.cNomor', 'cKode', 'cNama', 'cBidang', 'cPuskesmas')
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
        return Datatable::query(DB::table('tb' . $nama))
            ->showColumns('cKode', 'cNama')
            ->addColumn('action', function ($model) {
                return '<a href="#" onclick="pop_edit(' . (int)$model->cKode . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="#" onclick="del(' . (int)$model->cKode . ')" title="Hapus"><i class="glyphicon glyphicon-remove"></i></a>';
            })
            ->searchColumns('cKode', 'cNama')
            ->make();
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
            $data = DB::table('tbkeluardtl')->join('tbinventori','tbkeluardtl.inventori','=','tbinventori.id')->where('cNomor', $kode)->select('tbkeluardtl.*','harga')->get();
        } else {
            $data = DB::table('tb' . $tipe . 'dtl')->where('cNomor', $kode)->get();
        }
        return Response::json(array('data' => $data));
    }

    public function delete($nama, $id)
    {
        if ($nama == 'barang') {
            DB::table('tb' . $nama)->where('cKode', sprintf("%06d", $id))->delete();
        } else {
            DB::table('tb' . $nama)->where('cKode', sprintf("%04d", $id))->delete();
        }
        return Redirect::route('m-' . $nama);
    }

    //Puskesmas
    public function stok_alokasi()
    {
        //$kode = explode('-', Input::get('kode'));
        //$stok = DB::table('tbbarang')->join('tbstock', 'tbbarang.cKode', '=', 'tbstock.cKode')->groupBy('tbstock.cKode')->select(DB::raw('sum(nQtyReal) as sum'))->where('tbstock.cKode', $kode[0])->first();
        $data = DB::table('pkm_inventori')->where('id', Input::get('kode'))->first();
        //$stok=DB::table('tbinventori')->where('id',$kode[1])->first();
        return Response::json(array('brg' => $data));
    }

    public function get_pkm_alokasi()
    {
        return Datatable::query(DB::table('pkm_alokasi')->join('pkm_alokasidtl', 'pkm_alokasi.nomor', '=', 'pkm_alokasidtl.nomor')->groupBy('pkm_alokasi.nomor')->select('pkm_alokasi.*'))
            ->showColumns('nomor', 'tanggal', 'nama_unit')
            ->addColumn('action', function ($model) {
                $a = explode('-', $model->nomor);
                return '<a href="#" onclick="pop_editt(' . $a[1] . ')" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;&nbsp;' .
                '<a href="' . URL::to("nota/" . $model->nomor) . '" target="_blank" title="Cetak"><i class="glyphicon glyphicon-print"></i></a>&nbsp;&nbsp;' .
                '<a href="' . URL::to("save/" . $model->nomor) . '" target="_blank" title="Unduh"><i class="glyphicon glyphicon-save"></i></a>&nbsp;&nbsp;';
            })
            ->searchColumns('pkm_alokasi.nomor', 'nama_unit')
            ->make();
    }

}
