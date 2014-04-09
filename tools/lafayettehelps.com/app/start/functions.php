<?php

/* convenience functions for laravel */
global $session_message;
global $session_errors;

function debug($s)
{
	print "\n<pre class=\"debug\">\n";
	print_r($s);
	print "\n</pre>\n";
}

function error($s) {err($s);}

function err($s)
{
	Session::flash('status','error');
	global $session_errors;
	if ($session_errors) $session_errors .= "<br />";
	$session_errors .= $s;
	Session::flash('error', $session_errors);
}

function msg($s)
{
	global $session_message;
	if ($session_message) $session_message .= "<br />";
	$session_message .= $s;
	Session::flash('msg', $session_message);
}

function me()
{
	if (Auth::check())
		return Auth::user();
	else
		return new User();
}

function not_implemented()
{
	msg('not implemented yet');
	return Redirect::route('home');
}

function isSelf($user)
{
	if (Auth::check()) return $user == Auth::user();
	else return false;
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

// function hasPermissionTo($action, $object, $user = False)
// {
// 	global $permissions;
// 	if ($user) return $user->hasPermissionTo($action, $object);
// 	if (Auth::check()) return Auth::user()->hasPermissionTo($action, $object);
// 	if (isset($permissions[get_class($object)][$action]['anonymous'])) return $permissions[get_class($object)][$action]['anonymous'];
// 	return False;
//
// }

function saltPassword($p)
{
	$salt = Config::get('app.salt');
	$salted = $p . $salt;
	//return Hash::make($p . $salt);
	return $salted;
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

global $permissions;
$permissions['User']['add']['user'] = False;
$permissions['User']['edit']['user'] = False;
$permissions['Plea']['add']['user'] = True;
$permissions['Plea']['edit']['user'] = False;
$permissions['Organization']['add']['user'] = True;
$permissions['Organization']['edit']['user'] = False;

class myConfig
{
	// put configuration settings here

	private function __construct(){}

	public static function get($prop, $default = '')
	{
		if ( isset (self::$settings[$prop]) ) return self::$settings[$prop];
		else return $default;
	}
}

function show_reputation($user)
{
	$reputation_note = "This user has a reputation of " . $user->reputation . ". All users start with 75 and it goes up every time they help someone and down a little every time they request help. People can also get a reputation boost when someone fills out a recommendation for them.";
	$reputation_class="green";
	if ($user->reputation < 75) $reputation_class='yellow';
	if ($user->reputation < 50 ) $reputation_class='orange';
	if ($user->reputation < 25) $reputation_class='red';

	?>
	<div class="reputation_bar"
		style="width:100%;box-sizing:border-box;border:1px solid #777;border-radius:3px;overflow:hidden;background:black;"
		title="<?php print $reputation_note; ?>">
		<div class="reputation_color <?php print $reputation_class; ?>" style="width:<?php print ($user->reputation); ?>%;background-color:<?php print $reputation_class; ?>;">&nbsp;</div>
	</div>
	<?php

}

function show_mini_reputation($user)
{
	$reputation_note = "This user has a reputation of " . $user->reputation . ". All users start with 75 and it goes up every time they help someone and down a little every time they request help. People can also get a reputation boost when someone fills out a recommendation for them.";

	$reputation_class="green";
	if ($user->reputation < 75) $reputation_class='yellow';
	if ($user->reputation < 50 ) $reputation_class='orange';
	if ($user->reputation < 25) $reputation_class='red';

	?>
	<div class="reputation_bar_mini" style="width:50px;height:8px;border:1px solid black;border-radius:10px;box-sizing:border-box;background-color:black;overflow:hidden;"
		title="<?php print $reputation_note; ?>">
		<div class="reputation_color <?php print $reputation_class; ?>" style="width:<?php print ($user->reputation); ?>%;background-color:<?php print $reputation_class; ?>;">&nbsp;</div>
	</div>
	<?php
}


function show_search_bar($description, $search_url)
{
	?>
	
	<input class="form-control" name="search" placeholder="<?php print $description; ?>" id="searchbox" />
	<script type="text/javascript">
	
	
	</script>
	
	<?php
}

// don't close out the php tag!
