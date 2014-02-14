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

Route::get('/', function()
{

	// return View::make('play');
});


Route::get('play', array('before' => 'auth.basic', function()
{
	return View::make('play')
		->with('username', Auth::user()->username);
}));


Route::get('/authtest', array('before' => 'auth.basic', function()
{
    return View::make('hello');
}));


// Route group for API versioning
Route::group(array('prefix' => 'api/v1', 'before' => 'auth.basic'), function()
{
    // Route::resource('queueing', 'QueueingController');
    Route::get('queue', 'QueueingController@index');
    // Route::get('queue/join', 'QueueingController@join');

    Route::get('game', 'GameController@index');
    Route::post('game/move', 'GameController@move');
});



