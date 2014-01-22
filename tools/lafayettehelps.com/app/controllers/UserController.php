<?php

class UserController extends BaseController
{
	/**
	 * Show the profile for the given user.
	 */
	public function showDetail($id)
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

	public function doRegister()
	{
		return not_implemented();
	}

	public function doLogout()
	{
		Auth::logout();
		Session::put('status', 'success');
		Session::put('message','Successfully logged out');
		return Redirect::route('login');
	}

	public function doLogin()
	{
		if (Auth::check()) return Redirect::to('user/' . Auth::user()->id);
		if (Input::has('_token'))
		{
			$username = Input::get('username');
			$salted = Input::get('password') . Config::get('app.salt');
			if (Auth::attempt( array( 'username' => $username, 'password' => $salted), true ) )
			{
				Session::put('status','success');
				Session::put('message', 'Login successful. Welcome!');

				return Redirect::intended('user/' . Auth::user()->id);
			}
			else
			{
				Session::put('status','failed');
				Session::put('message', 'Login Failed... did you forget your password?');
				return Redirect::route('login')->withInput(Input::except('password'));
			}
		}
		return View::make('user.login');
	}

	public function doForgotForm()
	{
		if (Auth::check()) return Redirect::to('user/' . Auth::user()->id);
		return View::make('user.forgot');
	}

	public function doDelete($id)
	{
		if (isAdmin())
		{
			$user = User::find($id);
			$user->delete();
			Session::flash('status', 'success');
			msg('User Successfully Deleted');
			return View::make('user.index');
		}
		else
		{
			msg('You do not have permissions to delete users');
			return View::make('home');
		}
	}

	public function doBlock($id)
	{
		$user = User::find($id);
		$user->status = 'blocked';
		$user->save();
		Session::flash('status', 'success');
		msg('User Successfully Blocked');
		return View::make('user.profile', array('user'=>$user));
	}

	public function doAdd()
	{
		$user = new User;
		if (! me()->hasPermissionTo('add', $user));
		{
			Session::put('status','error');
			Session::put('message', 'You do not have permission to add users!');
			return Redirect::to('not-allowed');
		}

		if (Input::has('_token'))
		{
			$id = $user->validateAndUpdateFromArray(Input::all());

			return Redirect::to('user/'. $user->id);
		}
		return View::make('user.edit', array('user' => $user));
	}

	public function doEdit($id)
	{
			// If there is posted data and it validates,
			// jump to saveUser. Otherwise, show the form.
			$user = User::find($id);
			return View::make('user.edit', array('user' => $user));
	}

	public function doSave($id)
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
			$form_data = array_except(Input::all(), '_token');

			if($user->validateAndUpdateFromArray($form_data))
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
