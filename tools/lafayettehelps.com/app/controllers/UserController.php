<?php

class UserController extends BaseController
{
	/**
	 * Show the profile for the given user.
	 */
	public function showProfile($id)
	{
			if (! $user = User::find($id) ) App::abort(404);
			if (! Auth::check() ) App::abort(404);
			return View::make('user.profile', array('user' => $user));
	}

	public function showContactForm($id)
	{
			if (! $user = User::find($id) ) App::abort(404);
			if (! Auth::check() ) App::abort(404);
			return View::make('user.contact', array('user' => $user));
	}

	public function getIndex()
	{
			$users = User::all();
			return View::make('user.index', array('users' => $users));
	}

	public function doLogout()
	{
		Auth::logout();
		Session::put('status', 'success');
		Session::put('message','Successfully logged out');
		return Redirect::to('user/login');
	}

	public function doLogin()
	{
		if (Auth::check()) return Redirect::to('user/' . Auth::user()->id);
		if (Input::has('_token'))
		{
			$username = Input::get('username');
			$password = Input::get('password') . Config::get('app.salt');
			if (Auth::attempt( array( 'username' => $username, 'password' => $password), true ) )
			{
				return Redirect::intended('user/' . Auth::user()->id);
			}
			else
			{
				return Redirect::to('user/login')->withInput(Input::except('password'));
			}
		}
		return View::make('user.login');
	}

	public function addUser()
	{
		$user = new User;
		if (Input::has('_token'))
		{
			$id = $user->validateAndUpdateFromArray(Input::all());

			return Redirect::to('user/'. $user->id);
		}
		return View::make('user.edit', array('user' => $user));
	}

	public function editUser($id)
	{
			// If there is posted data and it validates,
			// jump to saveUser. Otherwise, show the form.
			$user = User::find($id);
			return View::make('user.edit', array('user' => $user));
	}

	public function saveUser($id)
	{
		$user = User::find($id);
		if ( ! Auth::user()->hasPermissionTo('edit',$user))
		{
			Session::put('status','error');
			Session::put('message', 'You do not have permission to edit this user!');
			return Redirect::to('user/'. $user->id . '/edit');
		}
		elseif (Input::has('password') && (Input::get('password') != Input::get('password_confirm','-1')))
		{
			Session::put('status','error');
			Session::put('message', 'Your passwords didn\'t match!');
			return Redirect::to('user/'. $user->id . '/edit')->withInput(Input::except('password'));
		}
		else
		{
			$input = Input::all();

			if($user->validateAndUpdateFromArray($input))
			{
				Session::put('status','success');
				Session::put('message', 'Saved!');
			}
			else
			{
				Session::put('status','error');
				Session::put('message','entered data did not validate');
				Input::flash();
			}
			return Redirect::to('user/'. $user->id . '/edit');
			//return ($this->editUser($user->id));
		}
	}
}
