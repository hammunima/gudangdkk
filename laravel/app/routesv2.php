<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('blank', array('uses' => 'LinkController@index'));
Route::get('login', array('uses' => 'LinkController@login'));
Route::post('login', array('uses' => 'UserController@is_login', 'as' => 'login'));

Route::get('hapusbarang', array('uses' => 'UserController@hapus'));
Route::get('barangjkn', array('uses' => 'UserController@jkn'));
Route::get('barangperwali', array('uses' => 'UserController@perwali'));


Route::get('hash', function(){
    //echo date('ym');
    echo Hash::make('tidakada');
});

Route::group(["before" => "auth"], function() {

    //Aplikasi Gudang
    Route::get('User_Logout', array('uses' => 'UserController@log_out', 'as' => 'logout'));
    Route::get('inventori', array('uses' => 'MasterController@index'));
    Route::get('inventori2', array('uses' => 'MasterController@index2'));

    Route::get('/', array('uses' => 'LinkController@index', 'as' => 'dash'));

    Route::get('MasterJenis', array('uses' => 'LinkController@m_jenis', 'as' => 'm-jenis'));
    Route::post('MasterJenis', array('uses' => 'MasterController@store_jenis', 'as' => 'm-jenis'));

    Route::get('MasterSatuan', array('uses' => 'LinkController@m_satuan', 'as' => 'm-satuan'));
    Route::post('MasterSatuan', array('uses' => 'MasterController@store_satuan', 'as' => 'm-satuan'));

    Route::get('MasterTipe', array('uses' => 'LinkController@m_tipe', 'as' => 'm-tipe'));
    Route::post('MasterTipe', array('uses' => 'MasterController@store_tipe', 'as' => 'm-tipe'));

    Route::get('MasterMerk', array('uses' => 'LinkController@m_merk', 'as' => 'm-merk'));
    Route::post('MasterMerk', array('uses' => 'MasterController@store_merk', 'as' => 'm-merk'));

    Route::get('MasterSumberAnggaran', array('uses' => 'LinkController@m_sumberanggaran', 'as' => 'm-sumberanggaran'));
    Route::post('MasterSumberAnggaran', array('uses' => 'MasterController@store_sumberanggaran', 'as' => 'm-sumberanggaran'));

    Route::get('MasterSupplier', array('uses' => 'LinkController@m_supplier', 'as' => 'm-supplier'));
    Route::post('MasterSupplier', array('uses' => 'MasterController@store_supplier', 'as' => 'm-supplier'));

    Route::get('MasterPuskesmas', array('uses' => 'LinkController@m_puskesmas', 'as' => 'm-puskesmas'));
    Route::post('MasterPuskesmas', array('uses' => 'MasterController@store_puskesmas', 'as' => 'm-puskesmas'));

    Route::get('MasterBidang', array('uses' => 'LinkController@m_bidang', 'as' => 'm-bidang'));
    Route::post('MasterBidang', array('uses' => 'MasterController@store_bidang', 'as' => 'm-bidang'));

    Route::get('MasterBarang', array('uses' => 'LinkController@m_barang', 'as' => 'm-barang'));
    Route::post('MasterBarang', array('uses' => 'MasterController@store_barang', 'as' => 'm-barang'));
    Route::get('DataBarang', array('uses' => 'HandlerController@get_data_barang', 'as' => 'get-barang'));

    Route::get('StockBarang', array('uses' => 'LinkController@m_stok', 'as' => 'm-stok'));
    Route::get('StokBarang', array('uses' => 'HandlerController@get_stok_barang', 'as' => 'get-stok'));

    Route::get('PenerimaanBarang', array('uses' => 'LinkController@penerimaan_barang', 'as' => 'm-penerimaan'));
    Route::post('PenerimaanBarang', array('uses' => 'MasterController@store_penerimaan', 'as' => 'm-penerimaan'));
    Route::get('TerimaBarang', array('uses' => 'HandlerController@get_trm_barang', 'as' => 'get-trm-barang'));
    Route::post('GetEachBarang', array('uses' => 'HandlerController@each_barang', 'as' => 'get-brg'));
    Route::post('GetDataTerima', array('uses' => 'HandlerController@get_data_trm', 'as' => 'get-trm'));

    Route::get('PengeluaranBarang', array('uses' => 'LinkController@pengeluaran_barang', 'as' => 'm-pengeluaran'));
    Route::post('PengeluaranBarang', array('uses' => 'MasterController@store_pengeluaran', 'as' => 'm-pengeluaran'));
    Route::get('KeluarBarang', array('uses' => 'HandlerController@get_klr_barang', 'as' => 'get-klr-barang'));
    Route::post('GetEachKLR', array('uses' => 'HandlerController@each_klr', 'as' => 'get-stok-klr'));
    Route::post('GetDataKeluar', array('uses' => 'HandlerController@get_data_klr', 'as' => 'get-klr'));

    Route::get('PenyesuaianBarang', array('uses' => 'LinkController@penyesuaian_barang', 'as' => 'm-penyesuaian'));
    Route::post('PenyesuaianBarang', array('uses' => 'MasterController@store_penyesuaian', 'as' => 'm-penyesuaian'));
    Route::get('SesuaiBarang', array('uses' => 'HandlerController@get_adj_barang', 'as' => 'get-adj-barang'));

    Route::post('GetNama', array('uses' => 'HandlerController@get_nama', 'as' => 'get-nama'));
    Route::post('GetNamaBrg', array('uses' => 'HandlerController@get_nama_barang', 'as' => 'get-nama-brg'));
    Route::get('delete/{nama}/{id}', array('uses' => 'HandlerController@delete', 'as' => 'delete'));
    Route::get('tabel/{nama}', array('uses' => 'HandlerController@get_tabel'));

    Route::post('LaporanStok', array('uses' => 'ReportController@report_stok', 'as' => 'report-stok'));
    Route::post('LaporanPenerimaan', array('uses' => 'ReportController@report_terima', 'as' => 'report-trm'));
    Route::post('LaporanPengeluaran', array('uses' => 'ReportController@report_keluar', 'as' => 'report-klr'));
    Route::post('LaporanPenyesuaian', array('uses' => 'ReportController@report_sesuai', 'as' => 'report-adj'));
    Route::get('nota/{id}', array('uses' => 'ReportController@cetak_nota2', 'as' => 'nota'));
    Route::get('save/{id}', array('uses' => 'ReportController@cetak_nota1', 'as' => 'savenota'));
    Route::get('expExcel/{id}', array('uses' => 'ReportController@exp_excel'));
    Route::get('expPDF/{id}', array('uses' => 'ReportController@exp_pdf'));
    Route::get('exp', array('uses' => 'ReportController@exp', 'as' => 'exp'));
    Route::post('detail', array('uses' => 'HandlerController@get_detail', 'as' => 'get-detail'));

    Route::get('cobanota/{id}', array('uses' => 'ReportController@cetak_nota', 'as' => 'nota'));
    Route::get('test/{id}', array('uses' => 'ReportController@cetak_nota2', 'as' => 'nota'));

    //Aplikasi Puskesmas
    //Puskeskmas
    Route::get('generate-pkm-masuk', array('uses' => 'MasterController@to_pkm'));

    Route::get('StockBarangPuskesmas', array('uses' => 'LinkController@m_stok_pkm', 'as' => 'm-stok-pkm'));
    Route::get('StokBarangPuskesmas', array('uses' => 'HandlerController@get_stok_pkm', 'as' => 'get-stok-pkm'));

    Route::get('AlokasiBarang', array('uses' => 'LinkController@alokasi_barang', 'as' => 'pkm-alokasi'));
    Route::post('AlokasiBarang', array('uses' => 'MasterController@store_pkmalokasi', 'as' => 'pkm-alokasi'));
    Route::get('AloBarang', array('uses' => 'HandlerController@get_pkm_alokasi', 'as' => 'get-pkm-alo'));
    Route::post('stok-alo', array('uses' => 'HandlerController@stok_alokasi', 'as' => 'get-stok-alo'));
    Route::post('data-alo', array('uses' => 'HandlerController@data_alokasi', 'as' => 'get-alo'));

    Route::get('PenerimaanBarangPuskesmas', array('uses' => 'LinkController@masuk_barang', 'as' => 'pkm-masuk'));
    Route::post('PenerimaanBarangPuskesmas', array('uses' => 'MasterController@store_pkmmasuk', 'as' => 'pkm-masuk'));
    Route::get('MasukBarangPuskesmas', array('uses' => 'HandlerController@get_pkm_masuk', 'as' => 'get-pkm-msk'));
    Route::post('GetEachBarangPuskesmas', array('uses' => 'HandlerController@each_barang_pkm', 'as' => 'get-brg-pkm'));
    Route::post('data-masukPuskesmas', array('uses' => 'HandlerController@data_masuk', 'as' => 'get-msk'));

    Route::get('Supplier-Puskesmas', array('uses' => 'LinkController@pkm_supplier', 'as' => 'pkm-supplier'));
    Route::post('Supplier-Puskesmas', array('uses' => 'MasterController@store_pkmsupplier', 'as' => 'pkm-supplier'));

    Route::get('User-Puskesmas', array('uses' => 'LinkController@pkm_user', 'as' => 'pkm-user'));
    Route::post('User-Puskesmas', array('uses' => 'MasterController@store_pkmuser', 'as' => 'pkm-user'));
    Route::get('User-Table', array('uses' => 'HandlerController@tabel_user', 'as' => 'tabel-user'));
    Route::post('Check-Username', array('uses' => 'HandlerController@cek_uname', 'as' => 'cek-uname'));

    Route::post('LaporanPenerimaanPuskesmas', array('uses' => 'ReportController@lap_terima_pkm', 'as' => 'lap-terima-pkm'));
    Route::post('LaporanPengeluaranPuskesmas', array('uses' => 'ReportController@lap_keluar_pkm', 'as' => 'lap-keluar-pkm'));
    Route::post('LaporanStokPuskesmas', array('uses' => 'ReportController@lap_stok_pkm', 'as' => 'lap-stok-pkm'));

    Route::get('cetakpkm/{id}', array('uses' => 'ReportController@cetak_notapkm'));

    //Admin DKK
    Route::get('StokPuskesmas', array('uses' => 'LinkController@dkk_stok_pkm', 'as' => 'dkk-stok-pkm'));
    Route::get('GetStokPuskesmas', array('uses' => 'HandlerController@dkk_get_stok', 'as' => 'dkk-get-stok'));

    Route::get('PengeluarangPuskesmas', array('uses' => 'LinkController@dkk_alokasi', 'as' => 'dkk-alokasi'));
    Route::get('dkkpengeluaran', array('uses' => 'HandlerController@dkk_get_alokasi', 'as' => 'dkk-get-alo'));

    Route::get('PenerimaanPuskesmas', array('uses' => 'LinkController@dkk_masuk', 'as' => 'dkk-masuk'));
    Route::get('dkkpenerimaan', array('uses' => 'HandlerController@dkk_get_masuk', 'as' => 'dkk-get-msk'));

    Route::get('MasterUnit', array('uses' => 'LinkController@dkk_unit', 'as' => 'dkk-unit'));
    Route::post('MasterUnit', array('uses' => 'MasterController@store_unit', 'as' => 'dkk-unit'));
    Route::get('GetUnit', array('uses' => 'HandlerController@get_unit', 'as' => 'get-unit'));

    Route::post('LaporanPenerimaanALL', array('uses' => 'ReportController@lap_terima', 'as' => 'lap-terima'));
    Route::post('LaporanPengeluaranALL', array('uses' => 'ReportController@lap_keluar', 'as' => 'lap-keluar'));
    Route::post('LaporanStokALL', array('uses' => 'ReportController@lap_stok', 'as' => 'lap-stok'));

    //Route::post('GetEachBarang', array('uses' => 'HandlerController@each_barang', 'as' => 'get-brg'));
    //Route::post('data-masuk', array('uses' => 'HandlerController@data_masuk', 'as' => 'get-msk'));
    Route::get('tabelpkm/{nama}', array('uses' => 'HandlerController@get_tabel_pkm'));
    Route::post('GetNamapkm', array('uses' => 'HandlerController@get_nama_pkm', 'as' => 'get-nama-pkm'));
    Route::post('GetNamaBarang', array('uses' => 'HandlerController@get_barang_pkm', 'as' => 'get-b'));
    Route::post('dkkdetail', array('uses' => 'HandlerController@dkk_get_detail', 'as' => 'dkk-get-detail'));
    Route::post('validasi', array('uses' => 'HandlerController@dkk_validate', 'as' => 'dkk-validate'));
    Route::post('check', array('uses' => 'HandlerController@dkk_cek', 'as' => 'dkk-cek'));
    Route::post('check2', array('uses' => 'HandlerController@pkm_cek', 'as' => 'pkm-cek'));
    Route::get('deletepkm/{nama}/{id}', array('uses' => 'HandlerController@delete_pkm', 'as' => 'delete-pkm'));
});



