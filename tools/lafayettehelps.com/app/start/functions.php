<?php

/* convenience functions for laravel */
function debug($s)
{
	print "\n<pre class=\"debug\">\n";
	print_r($s);
	print "\n</pre>\n";
}


Auth::loginUsingId(1); 

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

// don't close out the php tag!