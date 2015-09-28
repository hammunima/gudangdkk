<?php

class UserController extends BaseController
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

    public function hapus()
    {
        $data = DB::table('barang_hapus')->get();
        foreach ($data as $row) {
            DB::table('pkm_barang')->where('cKode', $row->cKode)->delete();
        }
        echo '<h2>BERHASIL DIHAPUS</h2>';
    }

    public function jkn()
    {
        $data = DB::table('barang_jkn')->get();
        foreach ($data as $row) {
            DB::table('pkm_barang')->insert(
                array(
                    'cKode' => substr_replace($row->cKode, "1", 0, 1),
                    'cNama' => $row->cNama . ' [JKN]',
                    'cKdJenis' => $row->cKdJenis,
                    'cJenis' => $row->cJenis,
                    'cKdMerk' => $row->cKdMerk,
                    'cMerk' => $row->cMerk,
                    'cKdTipe' => $row->cKdTipe,
                    'cTipe' => $row->cTipe,
                    'cKdSatuan' => $row->cKdSatuan,
                    'cSatuan' => $row->cSatuan,
                    'nharga' => $row->nHarga,
                    'ikondisi' => $row->iKondisi
                )
            );
        }
        echo '<h2>BERHASIL DITAMBAH</h2>';

    }

    public function perwali()
    {
        $data = DB::table('barang_perwali')->get();
        foreach ($data as $row) {
            DB::table('pkm_barang')->where('cKode', $row->cKode)->update(array('cPerwali' => $row->cPerwali));
        }
        echo '<h2>BERHASIL DITAMBAH PERWALI</h2>';

    }

    public function is_login()
    {
        $user = Input::get('username');
        $pass = Input::get('password');
        $credentials = [
            'username' => $user,
            'password' => $pass
        ];
        if (Auth::attempt($credentials, true)) {
            return Redirect::route('dash');
        } else {
            return Redirect::route('login')->with('message', 'Login Failed');
        }
    }

    public function log_in()
    {
        return View::make('login');
    }

    public function log_out()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::route('login');
    }

}
