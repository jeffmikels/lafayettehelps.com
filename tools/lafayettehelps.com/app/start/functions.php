<?php
date_default_timezone_set('America/New_York');

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


function do_cron()
{
	// jobs to run during cron
	/*
		1. check the deadlines of all active pleas
			- if the deadline is past, and status is still active, deactivate and process pledges
			- if the deadline is soon, send warning notification
		2. other jobs???
	*/
	$today = date('Y-m-d', time() - (24*60*60));
	$tomorrow = date('Y-m-d', time() + 24*60*60);
	$expired = Plea::with('pledges.author', 'author')->where('deadline', '>=', $tomorrow)->where('status', '<>', 'expired')->get();
	$expiring = Plea::with('pledges.author', 'author')->where('deadline', '=', $today)->where('status', '<>', 'expiring')->get();
	
	// process expired pleas
 	foreach ($expired as $plea)
	{
		// label as expired
		$plea->expire();
				
		// notify all interested parties
		foreach ($plea->pledges as $pledge)
		{
			$notification = array(
				'object' => $pledge,
				'type' => 'pledge',
				'reason' => 'expired'
			);
			$pledge->author->notify($notification);
		}
		
		// notify the original author
		$notification = array(
			'object' => $plea,
			'type' => 'plea',
			'reason' => 'expired'
		);
		$plea->author->notify($notification);		
		
	}
	
	// process expiring pleas
 	foreach ($expiring as $plea)
	{
		// label as expiring
		$plea->expiring();
		
		// notify all interested parties
		foreach ($plea->pledges as $pledge)
		{
			$notification = array(
				'object' => $pledge,
				'type' => 'pledge',
				'reason' => 'expiring'
			);
			$pledge->author->notify($notification);
		}
		// notify the original author
		$notification = array(
			'object' => $plea,
			'type' => 'plea',
			'reason' => 'expiring'
		);
		$plea->author->notify($notification);
	}
	return Response::json(TRUE);
}

function email($to_object, $from_object = '', $subject = '' , $content = '', $redirect = '')
{
	if ($from_object === '') $from_object = me();

	$to_object_type = get_class($to_object);
	if ($to_object_type == 'User')
	{
		$to_object->name = $to_object->getPublicName();
	}
	$from_object_type = get_class($from_object);
	if ($from_object_type == 'User')
	{
		$from_object->name = $from_object -> getPublicName();
	}

	$to = Array($to_object->email, $to_object->name);
	$from = array($from_object->email, $from_object->name);
	$subject = sprintf('[%s via lafayettehelps.com]: %s', $from_object->name, $subject);
	$data = array('content'=>$content, 'user'=>$from_object);
	if (!$to_object->email or !$content)
	{
		if (!$to_object->email) err('I couldn\'t find an email address on file for that ' . $to_object_type . '.');
		if (!$content) err('You didn\'t enter anything in the message field.');
		return Redirect::to(URL::previous());
	}

	Mail::send(array('emails.contact_html','emails.contact_plain'), $data, function($message) use ($to, $from, $subject)
	{
		$message->to($to[0], $to[1])->from('webmaster@lafayettehelps.com', 'lafayettehelps.com')->replyTo($from[0], $from[1])->subject($subject);
	});
	msg(sprintf('Your email to %s was sent successfully, I think', $to[1]));

	if ($redirect) return Redirect::to($redirect);
}

function site()
{
	$retval = new User();
	$retval->email = 'webmaster@lafayettecc.org';
	$retval->first_name = 'Pastor Jeff';
	$retval->last_name = 'Mikels';
	return $retval;
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

function isOrgAdmin($user = False, $org=NULL)
{
	// if we don't specify a user, then we set $user to me()
	if(! $user ) $user = me();
	return $user->isOrgAdmin($org);
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
$permissions['User']['edit']['self'] = True;
$permissions['User']['delete']['user'] = False;
$permissions['User']['delete']['self'] = False;
$permissions['User']['recommend']['user'] = True;
$permissions['User']['recommend']['self'] = False;
$permissions['User']['contact']['user'] = True;
$permissions['User']['contact']['self'] = False;
$permissions['Plea']['add']['user'] = True;
$permissions['Plea']['edit']['user'] = False;
$permissions['Plea']['edit']['self'] = True;
$permissions['Plea']['delete']['self'] = False;
$permissions['Plea']['reject']['user'] = False;
$permissions['Plea']['reject']['orgadmin'] = False;
$permissions['Pledge']['add']['user'] = True;
$permissions['Pledge']['edit']['user'] = False;
$permissions['Pledge']['edit']['self'] = True;
$permissions['Pledge']['delete']['self'] = True;
$permissions['Organization']['add']['user'] = True;
$permissions['Organization']['edit']['user'] = False;
$permissions['Organization']['edit']['orgadmin'] = True;
$permissions['Organization']['delete']['orgadmin'] = False;

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


function show_search_bar($object)
{
	?>

	<input class="form-control" name="search" id="searchbox" placeholder="Start typing to search" />
	<script type="text/javascript">
	$( "#searchbox" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "<?php echo route('search', array('object_name' => $object)); ?>",
				dataType: "json",
				type: 'POST',
				data: {
					query: request.term
				},
				success: function( items ) {
					console.log(items);
					response_list = new Array();
					contact_ids = new Array();
					//if (items.length > 0 ) response_list.push({ label: '-- HOUSEHOLDS ----', value: '' });
					for (i in items)
					{
						label = items[i].name;
						id = items[i].id;
						value = label;
						response_list.push( {
							label: label,
							value: value,
							id: id,
							data: items[i]
						} );
					}
					response(response_list);
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			console.log(ui);
			obj = ui.item.data;
			id = obj.id;
			document.location.href = '/<?php print $object; ?>/' + id;
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	}); // $("#searchbox")
	$("#searchbox").select();
	</script>

	<?php
}

// don't close out the php tag!
