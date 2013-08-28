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
			return View::make('user.edit', array('user' => $user));
	}
}