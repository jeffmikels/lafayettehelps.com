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

/*
Route::get('/', function()
{
	return View::make('hello');
});
*/

Route::get('/', 'HomeController@showWelcome');

Route::get('users', 'UserController@getIndex');
Route::get('user/{id}', 'UserController@showProfile');
Route::get('user/{id}/edit', 'UserController@editUser');
Route::post('user/{id}/edit', 'UserController@saveUser');

// CREATE FILTERS TO DETERMINE WHAT AUTHENTICATION IS NEEDED
// FOR EXAMPLE, ANY ROUTE WITH /edit IN THE NAME REQUIRES AT LEAST
// EDIT PERMISSIONS





/*
Route::get('users', function()
{
	$users = User::all();
	return View::make('users')->with('users', $users);
});

Route::get('user/{id}', function($id)
{
	$user = User::find($id);
	return View::make('user')->with('user', $user);
});

Route::get('user/{id}/edit', function($id)
{
	$user = User::find($id);
	return View::make('userEdit')->with('user', $user);
});
*/