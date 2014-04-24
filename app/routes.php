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

Route::get('/', array('as' => 'home', function()
{
	 return View::make('index');
}));


Route::get('play', array('as' => 'play', 'before' => 'auth.basic', function()
{
	return View::make('play')
		->with('username', Auth::user()->username);
}));

// Route group for API versioning
Route::group(array('prefix' => 'api/v1', 'before' => 'auth.basic'), function()
{
    Route::get('queue', 'QueueingController@index');
    Route::get('game', 'GameController@index');
    Route::post('game/move', 'GameController@move');
    Route::get('game/dice', 'GameController@previousRound');
});

/**
Login
 */

Route::get('account/login', array('as' => 'login', function () {
    return View::make('account.login');
}))->before('guest');

Route::post('account/guest', array('as' => 'login', function () {
    $lastGuest = User::whereRaw('username REGEXP \'^Guest\'')->orderBy('created_at','desc')->take(1)->first();
    $tmpNum = 1 + intval(str_replace("Guest", "", $lastGuest->username));
    $tmpUser = 'Guest'.$tmpNum;
    $tmpPass = rand(1,10000000);

    $newGuest = array(
        'username' => $tmpUser,
        'password' => $tmpPass
    );

    User::create(array(
        'username' => $tmpUser,
        'password' => Hash::make($tmpPass)
    ));

    if (Auth::attempt($newGuest)) {
        return Redirect::route('play');
    } else {
        //todo: log an error
        return Redirect::route('home')
            ->with('flash_notice', 'Something went wrong');
    }
}));

Route::post('account/login', function () {
    $user = array(
        'username' => Input::get('username'),
        'password' => Input::get('password')
    );

    if (Auth::attempt($user)) {
        return Redirect::route('home')
            ->with('flash_notice', 'You are successfully logged in.');
    }

    // authentication failure! lets go back to the login page
    return Redirect::route('login')
        ->with('flash_error', 'Your username/password combination was incorrect.')
        ->withInput();
});

/**
Logout
 */

Route::get('logout', array('as' => 'logout', function () {
    Auth::logout();

    return Redirect::route('home')
        ->with('flash_notice', 'You are successfully logged out.');
}))->before('auth');
