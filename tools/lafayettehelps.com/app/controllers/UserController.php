<?php

class UserController extends BaseController
{
	/**
	 * Show the profile for the given user.
	 */
	public function showProfile($id)
	{
			$user = User::find($id);
			return View::make('user.profile', array('user' => $user));
	}
	
	public function getIndex()
	{
			$users = User::all();
			return View::make('user.index', array('users' => $users));
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