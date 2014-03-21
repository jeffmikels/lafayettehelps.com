<?php

class UserController extends BaseController
{
	/**
	 * Show the profile for the given user.
	 */
	public function showDetail($id)
	{
			if (! $user = User::find($id) )
			{
				err('I could not find the user you were looking for.');
				return Redirect::route('users');
			}
			if (! Auth::check() )
			{
				err('You need to be logged in to view user records.');
				return Redirect::route('login');
			}
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
		// if the user is logged in and is not an Admin, we redirect to that user's profile
		if (Auth::check() and ! isAdmin()) return Redirect::to('user/' . Auth::user()->id);
		
		// if the form was submitted properly, we register a new user
		if (Input::has('_token'))
		{
			$new_user_fields = Input::except('_token','id','role','status','reputation','created_at','updated_at','deleted_at');
			// Registration validator is different from the user edit validator
			$validator_rules = Array(
				'username' => 'required|unique:users',
				'email' => 'required|email',
				'password' => 'required|confirmed|min:8',
				'phone' => 'regex:#^[ 0-9()-]+$#',
				'zip' => 'regex:#^[0-9]{5}(-[0-9]{4}){0,1}$#'
			);
			$validator = Validator::make($new_user_fields, $validator_rules);

			if ($validator->fails())
			{
				err('Registration Failed... see below for errors');
				return Redirect::route('register')->withInput(Input::except('password'))->withErrors($validator);
			}

			$user = new User();
			if ($user->validateAndUpdateFromArray(Input::except('_token','id')) )
			{
				// everything looks good, so now, we finish things up.
				// send confirmation email
				$this->sendConfirmationEmail($user);
				
				// say Thank You.
				msg('A confirmation email has been sent to you. You need to click on the link in that email before you will be allowed to log into this site.');
				return Redirect::route('login');
			}
		}

		return View::make('user.register', array('user' => new User()));
	}
	
	public function sendConfirmationEmail($user)
	{
		$confirmation_code = hash('sha256',$user->email);
		$confirmation_link = URL::route('userconfirm', array('id'=>$user->id, 'confirmation'=>$confirmation_code));
		$email = "
Thank you for registering a user account at lafayettehelps.com. In order for your account to be activated, you need to click on this confirmation code.

$confirmation_link";
		
		$subject = 'LafayetteHelps.com Email Verification';
		$headers = "From: webmaster@lafayettehelps.com\r\nReply-To: webmaster@lafayettehelps.com\r\n";
		
		mail($user->email, $subject, $email, $headers);
	}
	
	public function doConfirm($id, $confirmation)
	{
		// first, we attempt to find the user id in the database
		$user = User::find($id);
		if (! $user )
		{
			err('I could not find a user account to associate with this confirmation link.');
			return Redirect::route('register');
		}
		if ($confirmation == hash('sha256',$user->email))
		{
			$user->status = 'verified';
			$user->save();
			msg('Your user account has been confirmed, and you can now log in.');
			return Redirect::route('login');
		}
		
		err('You somehow clicked on an invalid email confirmation link.');
		return Redirect::route('register');
		
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
			$salted = saltPassword(Input::get('password'));
			if (Auth::attempt( array( 'username' => $username, 'password' => $salted), true ) )
			{
				msg('Login successful. Welcome!');
				return Redirect::intended('user/' . Auth::user()->id);
			}
			else
			{
				err('Login Failed... did you forget your password?');
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
		if (! me()->hasPermissionTo('add', $user))
		{
			dd(! me()->hasPermissionTo('add', $user));
			err('You do not have permission to add users!');
			return Redirect::route('home');
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
			err('You do not have permission to edit this user!');
			return Redirect::to('user/'. $user->id . '/edit');
		}
		elseif (Input::has('password') && (Input::get('password') != Input::get('password_confirm','-1')))
		{
			err('Your passwords didn\'t match!');
			return Redirect::to('user/'. $user->id . '/edit')->withInput(Input::except('password'));
		}
		else
		{
			$form_data = array_except(Input::all(), '_token');

			if($user->validateAndUpdateFromArray($form_data))
			{
				msg('User details saved successfully!');
			}
			else
			{
				err('entered data did not validate');
				Input::flash();
			}
			return Redirect::to('user/'. $user->id . '/edit');
			//return ($this->editUser($user->id));
		}
	}
}
