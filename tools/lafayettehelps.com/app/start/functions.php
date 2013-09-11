<?php

/* convenience functions for laravel */
function debug($s)
{
	print "\n<pre class=\"debug\">\n";
	print_r($s);
	print "\n</pre>\n";
}

function isAdmin($user = False)
{
	if ($user) return ($user->role=='administrator');
	if (! Auth::check() ) return False;
	return (Auth::user()->role == 'administrator');
}

function isOrgAdmin($user = False)
{
	if ($user) return ($user->isOrgAdmin());
	if (! Auth::check() ) return False;
	return (Auth::user()->isOrgAdmin());
}

function hasPermissionTo($action, $object, $user = False)
{
	global $permissions;
	if ($user) return $user->hasPermissionTo($action, $object);
	if (Auth::check()) return Auth::user()->hasPermissionTo($action, $object);
	if (isset($permissions[get_class($object)][$action]['anonymous'])) return $permissions[get_class($object)][$action]['anonymous'];
	return False;

}

/* TODO NOTES

PERMISSIONS
-----------
	users can edit their own profile
	users can leave recommendations on other profiles
	users can edit recommendations they have written
	users can submit requests, offers, and pledges
	users can comment on requests and offers without making pledge
	users can confirm receipt of fulfilled pledges on their own requests or offers
	users can claim to have fulfilled their own pledges and/or withdraw pledges

	moderators can edit/delete all content except user profiles
	moderators CAN NOT mark pledges as fulfilled
	moderators CAN NOT view last names

	organizational_admins can edit/delete all content
	organizational_admins can see last names
	organizational_admins can leave private organizational notes with "red flags"
	organizational_admins can create/edit a relationship with a user
	organizational_admins can verify requests

	administrators can edit/delete all content including usernames

	passwords are hashed and can't be seen or decrypted by anyone

*/

class myConfig
{
	// put configuration settings here

	private static $settings = Array
	(
		'salt' => 'it is very important to use a salt when hashing your passwords',
	);


	private function __construct(){}

	public static function get($prop, $default = '')
	{
		if ( isset (self::$settings[$prop]) ) return self::$settings[$prop];
		else return $default;
	}
}



// don't close out the php tag!
