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

	public function addNote()
	{
		$user_id = Input::get('user_id');
		$org_id = Input::get('org_id');
		$body = Input::get('body');
		$flag = Input::get('flag');
		$user = User::find($user_id);
		$org = Organization::find($org_id);
		if (! $user or ! $org )
		{
			err('Something went wrong with your submission');
			return Redirect::back();
		}
		if (! me()->isOrgAdmin($org) )
		{
			err('You do not have permission to leave organizational notes for that organization');
			return Redirect::back();
		}

		$note = new OrganizationNote();
		$note->organization_id = $org_id;
		$note->body = $body;
		$user->notes()->save($note);
		msg('Note left successfully');
		return Redirect::back();
	}

	public function getIndex()
	{
			//$users = User::all();
			$users = User::orderby('first_name')->where('status', '=', 'verified')->paginate(30);
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
				// if the user id is 1, then we have created the first user in the database
				// and we should set that user to be an administrator
				if ($user->id == 1)
				{
					$user->role = "administrator";
					$user->save();
				}
				
				// everything looks good, so now, we finish things up.
				// send confirmation email
				$this->sendConfirmationEmail($user);

				// say Thank You.
				$message_text = 'A confirmation email has been sent to you. You need to click on the link in that email before you will be allowed to log into this site.';
				
				if ($user->id == 1) $message_text = 'YOU HAVE CREATED THE FIRST USER. This user will be your administrative user unless you change it in the future.<br />' . $message_text;
				msg($message_text);
				return Redirect::route('login');
			}
		}

		return View::make('user.register', array('user' => new User()));
	}

	public function sendConfirmationEmail($user)
	{
		$confirmation_code = hash('sha256',$user->email);
		$confirmation_link = URL::route('userconfirm', array('id'=>$user->id, 'confirmation_code'=>$confirmation_code));
		$email = "
Thank you for registering a user account at lafayettehelps.com. In order for your account to be activated, you need to click on this confirmation code.

$confirmation_link";

		$subject = 'LafayetteHelps.com Email Verification';
		$headers = "From: webmaster@lafayettehelps.com\r\nReply-To: webmaster@lafayettehelps.com\r\n";

		mail($user->email, $subject, $email, $headers);
	}

	public function doConfirm($id, $confirmation_code)
	{
		// first, we attempt to find the user id in the database
		$user = User::find($id);
		if (! $user )
		{
			err('I could not find a user account to associate with this confirmation link.');
			return Redirect::route('register');
		}
		if ($confirmation_code == hash('sha256',$user->email))
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
		if (Auth::check()) return Redirect::route('userprofile', array('id' => Auth::user()->id));
		if (Input::has('_token'))
		{
			$username = Input::get('username');
			$salted = saltPassword(Input::get('password'));
			if (Auth::attempt( array( 'username' => $username, 'password' => $salted), true ) )
			{
				// authenticated, but let's check for account status
				$status = Auth::user()->status;
				if ($status == 'verified')
				{
					msg('Login successful. Welcome!');
					return Redirect::intended('user/' . Auth::user()->id);
				}
				elseif ($status == 'unverified')
				{
					msg('Login failed. You need to click the link in the confirmation email we sent you.');
					Auth::logout();
				}
				elseif ($status == 'blocked')
				{
					msg('Login failed. Your account has been blocked. Check with the site administrators to get your account reactivated.');
					Auth::logout();
				}
				else
				{
					msg('Login failed.');
					Auth::logout();
				}
			}
			else
			{
				err('Login Failed. Did you forget your password?');
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
		$target_user = User::find($id);
		if (me()->hasPermissionTo('delete', $target_user))
		{
			if (me() == $target_user)
			{
				Auth::logout();
				msg('You are now logged out, and the account will be deleted.');
			}
			$target_user->delete();
			Session::flash('status', 'success');
			msg('User Account Successfully Deleted');
			return Redirect::route('users');
		}
		else
		{
			msg('You do not have permissions to delete users');
			return Redirect::route('home');
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
		elseif (Input::has('password') && (Input::get('password') != Input::get('password_confirmation','-1')))
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
