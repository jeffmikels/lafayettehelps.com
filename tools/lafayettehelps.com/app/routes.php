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


Route::get('/test', function()
{
	$user = User::find(1);
	$uc = new UserController();
	$uc->sendConfirmationEmail($user);
	dd('hello');
});

// HOME AND ADMIN
Route::get('/', array('as'=>'home', 'uses' => 'HomeController@showWelcome'));
Route::get('not-allowed', array('as'=>'not-allowed', function()
{
	msg('Sorry, you aren\'t allowed to do that.');
	return Redirect::route('home');
}));


Route::get('info', array('as'=>'info', function()
{
	return View::make('info');
}));

// ADMIN ROUTES


// ACCOUNT ROUTES
Route::get('register', array('as'=>'register', 'uses'=>'UserController@doRegister'));
Route::post('register', array('before' => 'csrf', 'uses' => 'UserController@doRegister'));
Route::get('login', array('as'=>'login', 'uses'=>'UserController@doLogin'));
Route::post('login', array('before' => 'csrf', 'uses' => 'UserController@doLogin'));
Route::get('logout', array('as'=>'logout', 'uses'=>'UserController@doLogout'));


// USER ROUTES
Route::get('users', array('as'=>'users', 'uses'=> 'UserController@getIndex'));
Route::get('user/add', array('as'=>'adduser', 'uses'=>'UserController@doAdd'));
Route::post('user/add', array('before' => 'csrf', 'uses' => 'UserController@doAdd'));

Route::get('user', array('as'=>'profile', function()
{
	if(Auth::check())
	{
		$id = Auth::user()->id;
		return Redirect::route('userprofile', array('id'=>$id));
	}
	else;
	{
		return Redirect::route('login');
	}
}));
Route::get('user/{id}', array('as'=>'userprofile', 'uses'=>'UserController@showDetail'));
Route::get('user/{id}/edit', array('as'=>'useredit', 'uses'=>'UserController@doEdit'));
Route::post('user/{id}/edit', array('before'=>'csrf', 'uses' => 'UserController@doSave'));
Route::get('user/{id}/delete', array('before'=>'csrf', 'uses' => 'UserController@doDelete'));
Route::get('user/{id}/confirm/{confirmation}', array('as'=>'userconfirm', 'uses' => 'UserController@doConfirm'));

// RECOMMENDATIONS
Route::get('recommendation/{id}', array('as'=>'recommendationdetail', 'uses'=>'RecommendationController@showDetail'));
Route::get('recommendation/{id}/edit', array('as'=>'recommendationedit', 'uses'=>'RecommendationController@doEdit'));
Route::post('recommendation/{id}/edit', array('before'=>'csrf', 'as'=>'recommendationedit', 'uses'=>'RecommendationController@doEdit'));
Route::get('recommendation/{id}/delete', array('as'=>'recommendationdelete', 'uses'=>'RecommendationController@doDelete'));
Route::get('recommendations/for/user/{user_id}', array('as'=>'recommendationsforuser', 'uses'=>'RecommendationController@showRecommendationsForUser'));
Route::get('recommendation/by/user/{user_id}', array('as'=>'recommendationsbyuser', 'uses'=>'RecommendationController@showRecommendationsByUser'));
Route::get('recommendation/for/user/{user_id}/add', array('as'=>'recommendationadd', 'uses'=>'RecommendationController@doAdd'));
Route::post('recommendation/for/user/{user_id}/add', array('before'=>'csrf', 'as'=>'recommendationadd', 'uses'=>'RecommendationController@doAdd'));


// ORGANIZATION ROUTES
Route::get('organizations', array('as'=>'organizations', 'uses'=>'OrganizationController@getIndex'));
Route::get('organization/add', array('as'=>'addorganization', 'uses'=>'OrganizationController@add'));
Route::post('organization/add', array('before' => 'csrf', 'uses' => 'OrganizationController@add'));

Route::get('organization/{id}', 'OrganizationController@showDetail');
Route::get('organization/{id}/edit', 'OrganizationController@edit');
Route::post('organization/{id}/edit', array('before'=>'csrf', 'uses' => 'OrganizationController@edit'));
Route::get('relationships', function()
{
	if (Auth::check())
		if (isOrgAdmin() || isAdmin())
		{
			$users = User::all();
			$relationship_details = array();
			foreach ($users as $user)
			{
				if (count($user->organizations) == 0) $relationship_details[] = array( 'user' => $user, 'organization' => NULL, 'relationship_type' => 'NONE');
				foreach ($user->organizations as $organization) $relationship_details[] = array('user' => $user, 'organization' => $organization, 'relationship_type' => $organization->pivot->relationship_type);
			}
			return View::make('organization.relationships', array( 'relationships' => $relationship_details ));
		}
});


// PLEA ROUTES
Route::get('pleas', array('as' => 'pleas', function()
{
   return View::make('plea.list', array('pleas' => Plea::paginate(20)));
}));

Route::get('plea/add', array('as'=>'addplea', 'uses'=>'PleaController@add'));
Route::post('plea/add', array('before' => 'csrf', 'uses' => 'PleaController@add'));
Route::get('plea/{id}', array('as'=>'pleadetail', 'uses'=>'PleaController@showDetail'));
Route::get('plea/{id}/edit', array('as'=>'pleaedit', 'uses'=>'PleaController@edit'));
Route::post('plea/{id}/edit', array('before'=>'csrf', 'uses' => 'PleaController@edit'));
Route::get('pleas/by/user/{user_id}', array('as' => 'pleasbyuser', 'uses' => 'PleaController@showPleasByUser'));


// PLEDGES ROUTES
Route::get('pledges/by/user/{user_id}', array('as' => 'pledgesbyuser', 'uses' => 'PledgeController@showPledgesByUser'));
Route::get('pledges/by/plea/{plea_id}', array('as' => 'pledgesbyplea', 'uses' => 'PledgeController@showPledgesByPlea'));


// OFFER ROUTES
Route::get('offers', function()
{
   return View::make('default', array('objects' => Offer::all(), 'class'=>'Offer'));
});
Route::get('offer/add', array('as'=>'addoffer', 'uses'=>'OfferController@add'));
Route::post('offer/add', array('before' => 'csrf', 'uses' => 'OfferController@add'));

Route::get('offer/{id}', 'OfferController@showDetail');
Route::get('offer/{id}/edit', 'OfferController@edit');
Route::post('offer/{id}/edit', array('before'=>'csrf', 'uses' => 'OfferController@edit'));


// COMMENTS ROUTES
Route::post('comment/add', array('before'=>'csrf', 'uses' => 'CommentController@add'));


// PASSWORD VIEWS
Route::get('password/forgot', array('as'=>'forgot', 'uses'=>'UserController@doForgotForm'));
Route::post('password/forgot', array('before'=>'csrf', function()
{
	$credentials = array();
	if (Input::has('username')) $credentials['username'] = Input::get('username');
	elseif (Input::has('email')) $credentials['email'] = Input::get('email');
	else
	{
		msg('No email was set.');
		return Redirect::route('forgot');
	}
	Log::info('Preparing to send email');
	Password::remind($credentials, function($message)
	{
	    $message->subject('LafayetteHelps.com Password Reminder');
	});
	
	msg('Please check your email for a password reminder.');
	return Redirect::route('login');
}));

Route::get('password/reset/{token}', function($token)
{
	if (Auth::check()) return Redirect::to('user/' . Auth::user()->id . '/edit');
    else return View::make('password.reset')->with('token', $token);
});

Route::post('password/reset/{token}', function()
{
	$credentials = array();
	if (Input::has('username')) $credentials['username'] = Input::get('username');
	elseif (Input::has('email')) $credentials['email'] = Input::get('email');
	return Password::reset($credentials, function($user, $password)
	{
		$user->setPassword($password);
		$user->save();
		return Redirect::route('login');
	});
});


// CREATE FILTERS TO DETERMINE WHAT AUTHENTICATION IS NEEDED
// FOR EXAMPLE, ANY ROUTE WITH /edit IN THE NAME REQUIRES AT LEAST
// EDIT PERMISSIONS
