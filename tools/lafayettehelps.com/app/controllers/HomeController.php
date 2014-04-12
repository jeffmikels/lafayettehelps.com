<?php

class HomeController extends BaseController {

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
		$urgent = Plea::take(4)->orderBy('deadline', 'DESC')->get();
		$newest = Plea::take(4)->orderBy('created_at', 'DESC')->get();
		return View::make('hello', array('urgent' => $urgent, 'newest' => $newest));
	}

	public function showAdmin()
	{
		// Admin page should show links to
		// Manage users
		// Manage organizations
		// Manage Requests
		return View::make('admin');
	}

}