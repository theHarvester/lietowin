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

Route::post('account/guest', array('as' => 'guest_login', function () {
    $lastGuest = User::whereRaw('username REGEXP \'^Guest[0-9]*$\'')->orderBy('created_at','desc')->take(1)->first();
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
            ->with('flash_notice', 'Something went wrong, the authorities have been notified. Is that a red dot on your shirt?');
    }
}));

Route::post('account/register', array('as' => 'register', function () {
    $username = Input::get('username');
    $email = Input::get('email');
    $password = Input::get('password');

    if(!preg_match('/^[a-zA-Z0-9-_]+$/', $username) || preg_match('/^Guest[0-9]*$/', $username) || strlen($username) > 60){
        return Redirect::route('home')
            ->with('flash_notice', 'Invalid username format. Username\'s can only contain letters, numbers, underscores and dashes.');
    }

    if(!empty($email)){
        $emailValidator = Validator::make(
            array('email' => $email),
            array('email' => 'email|unique:users')
        );
        if($emailValidator->fails()){
            return Redirect::route('home')
                ->with('flash_notice', 'The email address provided has an incorrect format or is registered with another user.');
        }
    } else {
        $email = null;
    }

    $usernameTaken = User::where('username', '=', $username)->first();
    if(!empty($usernameTaken)){
        return Redirect::route('home')
            ->with('flash_notice', 'That username has already been used');
    }

    $newUser = array(
        'username' => $username,
        'password' => $password
    );

    User::create(array(
        'username' => $username,
        'email' => $email,
        'password' => Hash::make($password)
    ));

    if (Auth::attempt($newUser)) {
        return Redirect::route('play');
    } else {
        //todo: log an error
        return Redirect::route('home')
            ->with('flash_notice', 'Something went wrong, the authorities have been notified. Is that a red dot on your shirt?');
    }
}));

Route::post('account/login', function () {
    $username = Input::get('username');
    $password = Input::get('password');

    // check if it's a username or email attempt
    if(preg_match('/^[a-zA-Z0-9-_]+$/', $username)){
        $user = array(
            'username' => $username,
            'password' => $password
        );
    } else {
        $user = array(
            'email' => $username,
            'password' => $password
        );
    }

    if (Auth::attempt($user)) {
        return Redirect::route('play')
            ->with('flash_notice', 'You are successfully logged in.');
    }

    // authentication failure! lets go back to the login page
    return Redirect::route('home')
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
