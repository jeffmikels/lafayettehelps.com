## Lafayette Helps

lafayettehelps is a website to facilitate peer to peer financial assistance. More information on the intent of the site is to be found in the "info.md" page located at `tools/lafayettehelps.com/app/views/info.md`.

The site is built with Laravel 4.0.

###Installation Notes

*These instructions assume you know how to set up a web server with php and a database. For more help installing, use the documentation at the Laravel website. Remember, this is version 4.0.*

Here are the key things to remember:

* Make sure the web server points to the `tools/lafayettehelps.com/public/` directory as the Document Root and is enabled to use `index.php` as the Index Document. If you are running Apache, the .htaccess file should take care of the rest, but if you are running nginx, make sure your location directive has a line like this:

	    try_files $uri $uri/ /index.php$is_args$args;
 
* All dependencies are included in the repository, so you don't need to run the `composer install` command in the Laravel root.
* Make sure the `tools/lafayettehelps.com/app/storage` directory is writable by the php process.
* Double-check the permissions to make sure the web server process can access everything in the public directory (the web server needs read and execute permissions on all directories above "public" too).
* In the directory `tools/lafayettehelps.com/app/config` copy `database.php.sample` to `database.php` and edit the file to fit your database settings.
* In the directory `tools/lafayettehelps.com/` execute the commands `php artisan optimize` to optimize the class loaders and then `php artisan migrate` to install the database tables.
* Visit the url: `http://your-installation/register` to create your first user. This user will be your administrative user, but you can change it's role later on.

Once you are logged in, you should be able to use your administrative privileges to move around the site and do what needs to be done!

###For Developers


####TODO Items

Priorities:

* We need a great homepage to help people understand what's going on (graphic design improvements are a must for usability) Sites for inspiration here: kickstarter.com / gofundme.com / begslist.org
* We need an expiration system so that requests get disabled at their deadline. (This is mostly implemented, but it isn't finished)
* We need a notification system so that users get appropriate notifications of new, updated, expiring requests. I want users to select their own favorite notification methods between SMS & Email. I'm using GoogleVoice as an SMS gateway on our server already.
* We need a mechanism to actually handle the donations. Currently, the church is willing to use its PayPal account to handle the donations, but we need the site to interface with the PayPal API to do pre-authorization of payment when the pledge is made and final processing of payment when the deadline is reached but only if the total pledges equal or exceed the stated need. (Like kickstarter, I want to guarantee our donors that they will only be charged if their donation actually makes a difference!)
* We need a comment and recommendation moderation system of some kind... Either we allow admins to moderate comments and recommendations, or we could do something more "social" by allowing people with certain levels of reputation to automatically be considered moderators.
* I have also toyed with the idea of letting respected users "vote up" or "vote down" certain requests or to somehow sort the request lists by user reputation, popularity, or by other social factors.



General:

* CONTACT FORMS -- implement "abuse report" (reduce reputation & add note to user)
* Implement the Organization Moderation system
	
Existing User Edits:

* TODO: Add validation so that only administers can edit certain fields
* TODO: Add "blocked" to the status variables and add "deleted"
* TODO: Consider totally removing "username" and just using email address.

User Profile Page:

* TODO: Add uploaded image avatar support

Requests:

* TODO: Add a thumbs up system to let logged-in users vote up requests (one vote per user per request).
* TODO: Add simple two-click moderation for requests.
* Add system for expiring old requests (removing from public pages and alerts but preserving on all related user pages)
* Allow users to close unfulfilled requests
* Allow users to confirm or deny that a pledge was fulfilled

Notifications:

* Add a system for handling notifications of new pleas, expiring pleas, etc.

Organizations:

* TODO: Add organization moderation interface for Admins.

Financials:

* Integrate with Lafayette Community Church's PayPal account
* "pre-authorize" pledges
* "finalize" pledges

Core:

* TODO: Add a "Request Moderator" user role.
* Add universal Moderation features
