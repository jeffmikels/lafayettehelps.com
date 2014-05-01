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
	$note = OrganizationNote::find(2);

	return Response::json($note->creator->getName());
	// Header('Content-type: text/plain');
	// var_dump(User::find(3)->isOrgAdmin($org));
	// dd();
});

// HOME AND ADMIN
Route::get('/', array('as'=>'home', 'uses' => 'HomeController@showWelcome'));
Route::get('not-allowed', array('as'=>'not-allowed', function()
{
	msg('Sorry, you aren\'t allowed to do that.');
	return Redirect::route('home');
}));


// STATIC PAGES
Route::get('info', array('as'=>'info', function()
{
	return View::make('info');
}));


Route::get('{object_type}/{id}/contact', array('as'=>'contact', function($object_type, $id)
{
	switch ($object_type)
	{
		case 'user':
			$object = User::find($id);
			$object->name = $object->getName();
			break;
		case 'organization':
			$object = Organization::find($id);
			break;
		default:
			err('I couldn\'t figure out what you were trying to do.');
			return Redirect::route('home');
	}
	return View::make('contact', array('object'=>$object, 'object_type'=>$object_type));

}));

Route::post('{object_type}/{id}/contact', array('as'=>'sendmail', 'before'=>'csrf', function($object_type, $id)
{
	if(! Auth::check())
	{
		err('You need to be logged in to do that.');
		return Redirect::route('register');
	}
	switch ($object_type)
	{
		case 'user':
			$target = User::find($id);
			$target->name = $target->getName();
			break;
		case 'organization':
			$target = Organization::find($id);
			break;
		default:
			err('I couldn\'t figure out what you were trying to do.');
			return Redirect::route('home');
	}
	$sender = me();
	$sender->name = $sender->getPublicName();
	$from = array($sender->email, $sender->name);

	$to = array($target->email, $target->name);

	$content = Input::get('content','');
	$subject = sprintf('[%s via lafayettehelps.com]: %s', $sender->name, Input::get('subject',''));
	$data = array('content'=>$content, 'user'=>$sender);
	if (!$target->email or !$content)
	{
		if (!$target->email) err('I couldn\'t find an email address on file for that ' . $object_type . '.');
		if (!$content) err('You didn\'t enter anything in the message field.');
		return Redirect::to(URL::previous());
	}
	Mail::send(array('emails.contact_html','emails.contact_plain'), $data, function($message) use ($to, $from, $subject)
	{
		$message->to($to[0], $to[1])->from('webmaster@lafayettehelps.com', 'lafayettehelps.com')->replyTo($from[0], $from[1])->subject($subject);
	});
	msg('Message Sent Successfully');
	switch($object_type)
	{
		case 'user':
			return Redirect::route('userprofile', array('id' => $object->id));
		case 'organization':
			return Redirect::route('orgprofile', array('id' => $object->id));
	}
	return Redirect::route('home');
}));


Route::get('report/{id}/{by}', array('as'=>'report', function($id, $by){
	msg('user ' . $id . ' reported as abusive by ' . $by);
	return Redirect::route('home');
}));


// ACCOUNT ROUTES
Route::get('register', array('as'=>'register', 'uses'=>'UserController@doRegister'));
Route::post('register', array('before' => 'csrf', 'uses' => 'UserController@doRegister'));
Route::get('login', array('as'=>'login', 'uses'=>'UserController@doLogin'));
Route::post('login', array('before' => 'csrf', 'uses' => 'UserController@doLogin'));
Route::get('logout', array('as'=>'logout', 'uses'=>'UserController@doLogout'));
/* PASSWORD RELATED ROUTES ARE BELOW */


// USER ROUTES
Route::get('users', array('as'=>'users', 'uses'=> 'UserController@getIndex'));
Route::get('user/add', array('as'=>'adduser', 'uses'=>'UserController@doAdd'));
Route::post('user/add', array('before' => 'csrf', 'uses' => 'UserController@doAdd'));
// Route::get('profile', array('as'=>'profile', function() {return Redirect::route('dashboard');}));
Route::get('user', array('as'=>'dashboard', function()
{
	if(Auth::check())
	{
		return View::make('user.dashboard', array('user'=>me()));
	}
	else
	{
		msg('Please Log In');
		return Redirect::route('login');
	}
}));
Route::get('user/{id}/profile', array('as'=>'userprofile', 'uses'=>'UserController@showDetail'));
Route::get('user/{id}/edit', array('as'=>'useredit', 'uses'=>'UserController@doEdit'));
Route::post('user/{id}/edit', array('before'=>'csrf', 'uses' => 'UserController@doSave'));
Route::get('user/{id}/confirm/{confirmation_code}', array('as'=>'userconfirm', 'uses' => 'UserController@doConfirm'));
/* USER ADMINISTRATION */
Route::get('user/{id}/delete', array('as'=>'userdelete', 'uses' => 'UserController@doDelete'));
Route::get('user/{id}/ban', array('uses' => 'UserController@ban'));
Route::post('user/addnote', array('before' => 'csrf', 'uses' => 'UserController@addNote'));


// RECOMMENDATIONS
Route::get('recommendation/{id}', array('as'=>'recommendationdetail', 'uses'=>'RecommendationController@showDetail'));
Route::get('recommendation/{id}/edit', array('as'=>'recommendationedit', 'uses'=>'RecommendationController@doEdit'));
Route::post('recommendation/{id}/edit', array('before'=>'csrf', 'as'=>'recommendationedit', 'uses'=>'RecommendationController@doEdit'));
Route::get('recommendation/{id}/delete', array('as'=>'recommendationdelete', 'uses'=>'RecommendationController@doDelete'));
Route::get('recommendations/for/user/{user_id}', array('as'=>'recommendationsforuser', 'uses'=>'RecommendationController@showRecommendationsForUser'));
Route::get('recommendation/by/user/{user_id}', array('as'=>'recommendationsbyuser', 'uses'=>'RecommendationController@showRecommendationsByUser'));
Route::get('recommendation/for/user/{user_id}/add', array('as'=>'recommendationadd', 'uses'=>'RecommendationController@showForm'));
Route::post('recommendation/for/user/{user_id}/add', array('before'=>'csrf', 'uses'=>'RecommendationController@doAdd'));



// ORGANIZATION ROUTES
Route::get('organizations', array('as'=>'organizations', 'uses'=>'OrganizationController@getIndex'));
Route::get('organization/add', array('as'=>'addorganization', 'uses'=>'OrganizationController@add'));
Route::post('organization/add', array('before' => 'csrf', 'uses' => 'OrganizationController@add'));
Route::get('organization/{id}', array('as'=>'orgprofile', 'uses'=>'OrganizationController@showDetail'));
Route::get('organization/{id}/edit', 'OrganizationController@edit');
Route::post('organization/{id}/edit', array('before'=>'csrf', 'uses' => 'OrganizationController@edit'));
Route::get('relationships', array('as'=>'relationships', function()
{
	if (Auth::check())
	{
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
	}
	err('You are not allowed to do that.');
	return Redirect::route('home');
}));
// ORGANIZATION ADMIN
Route::get('organization/{id}/approve', array('uses' => 'OrganizationController@approve'));
Route::get('organization/{id}/disapprove', array('uses' => 'OrganizationController@disapprove'));


// PLEA ROUTES
Route::get('pleas', array('as' => 'pleas', function()
{
   return View::make('plea.list', array('pleas' => Plea::paginate(20)));
}));

Route::get('plea/add', array('as'=>'pleaadd', 'uses'=>'PleaController@add'));
Route::post('plea/add', array('before' => 'csrf', 'uses' => 'PleaController@add'));
Route::get('plea/{id}', array('as'=>'pleadetail', 'uses'=>'PleaController@showDetail'));
Route::get('plea/{id}/edit', array('as'=>'pleaedit', 'uses'=>'PleaController@edit'));
Route::post('plea/{id}/reject', array('as'=>'pleareject', 'uses'=>'PleaController@reject'));
Route::post('plea/{id}/edit', array('before'=>'csrf', 'uses' => 'PleaController@edit'));
Route::get('pleas/by/user/{user_id}', array('as' => 'pleasbyuser', 'uses' => 'PleaController@showPleasByUser'));


// PLEDGES ROUTES
//Route::get('pledge/add', array('as'=>'pledgeadd', 'uses'=>'PledgeController@add'));
Route::post('pledge/add', array('before' => 'csrf', 'uses' => 'PledgeController@add'));
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


// ADMIN/MODERATION PAGE
Route::get('admin', function(){return View::make('admin');});


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

// JSON VIEWS
Route::get('{object_name}/json', array('as' => 'json', function($object_name)
{
	switch ($object_name)
	{
		case 'users':
			$object = User::all();
			break;
		case 'organizations':
			$object = Organization::all();
			break;
		default:
			$object = '';
	}
	return Response::json($object);
}));
Route::get('{object_name}/{id}/json', array('as' => 'json', function($object_name, $id)
{
	switch ($object_name)
	{
		case 'user':
			$object = User::find($id);
			break;
		case 'organization':
			$object = Organization::find($id);
			break;
		default:
			$object = '';
	}
	return Response::json($object);
}));

Route::post('{object_name}/search/json', array('as' => 'search', function($object_name)
{
	if (! Input::has('query')) return Response::json(Array());
	$query = Input::get('query', '');
	$query = '%' . str_replace(' ','%', $query) . '%';
	switch ($object_name)
	{
		case 'user':
			$results = User::where('email','LIKE',$query)->orWhere('first_name','LIKE',$query)->get()->toArray();
			foreach ($results as $key => $user)
			{
				$full_name = $user['first_name'] . ' ' . substr($user['last_name'],0,1);
				$results[$key]['name'] = $full_name;
				unset($results[$key]['last_name']);
			}
			break;
		case 'organization':
			$results = Organization::where('name','LIKE',$query)->get();
			break;
		default:
			$results = Array();
	}
	return Response::json($results);
}));


// CREATE FILTERS TO DETERMINE WHAT AUTHENTICATION IS NEEDED
// FOR EXAMPLE, ANY ROUTE WITH /edit IN THE NAME REQUIRES AT LEAST
// EDIT PERMISSIONS
