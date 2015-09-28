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

    public function is_login()
    {
        $user = Input::get('u');
        $pass = Input::get('p');
        $credentials = [
            'username' => $user,
            'password' => $pass
        ];
        if (Auth::attempt($credentials, true)) {
            return Redirect::route('dash');
        } else {
            return Redirect::route('login')->with('message', 'Login Failed');
        }
		//dd(Auth::attempt($credentials));
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
