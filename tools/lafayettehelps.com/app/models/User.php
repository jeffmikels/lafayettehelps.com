<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

//class User extends Eloquent {}

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	// options arrays are created like so:
	// the key is what gets stored in the database
	// the value is what we use for display
	protected $role_options = Array('user' => 'User', 'editor' => 'Editor', 'administrator' => 'Administrator');
	protected $status_options = Array('unverified' => 'Unverified','verified' => 'Verified');
	protected $properties = Array('id','username','password','email','first_name','last_name','phone','address','city','state','zip','reputation','status','role');
	protected $public_properties = Array('username','first_name','phone','reputation');

	// some properties should never be updated by the web form
	// we set their validations to "refuse"
	protected $validations = Array(
		'default' => '#^[\d\w\s\.]{1,64}$#',
		'username' => '#^[\d\w\.]{1,32}$#',
		'email' => '#^[a-zA-Z0-9.!\#$%&\'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$#',
		'phone' => '#^[ 0-9()\-]+$#',
		'zip' => '#^[0-9]{5}(-[0-9]{4}){0,1}$#',
		'role' => '#^user|editor|administrator$#',
		'status' => '#^unverified|verified$#',
		'reputation' => 'refuse',
		'created_at' => 'refuse',
		'updated_at' => 'refuse',
		'deleted_at' => 'refuse'
	);


	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}


	public function organizations()
	{
		return $this->belongsToMany('Organization','relationships','user_id','organization_id')->withPivot('relationship_type');
	}


	public function getRoleOptions()
	{
		return $this->role_options;
	}

	public function getStatusOptions()
	{
		return $this->status_options;
	}
	public function getProperties()
	{
		return $this->properties;
	}
	public function getPublicProperties()
	{
		return $this->public_properties;
	}

	public function validateContent($prop, $content)
	{
		$property_exists = false;
		$content_matches = false;
		if (in_array($needle = $prop, $haystack = $this->getProperties())) $property_exists = true;
		/* for now, we assume the content matches */

		$pattern = $this->validations['default'];
		if (isset($this->validations[$prop])) $pattern = $this->validations[$prop];
		if ($pattern == 'refuse') return false;
		$content_matches = preg_match($pattern, $content);

		return ($property_exists && $content_matches);
	}


	public function validateAndUpdateFromArray($arr)
	{
		foreach ($arr as $prop=>$value)
		{
			if ($value && (! $this->validateContent($prop, $value)))
			{
				//debug($prop . " did not validate");
				return False;
			}
		}
		// handle password changes
		if (isset($arr['password']))
			if (! isset($arr['password_confirm'])) return False;
			elseif ($arr['password'] !== $arr['password_confirm']) return False;
		unset($arr['password_confirm']);

		if ($this->updateFromArray($arr))
			return $this->id;
		else
			return False;

	}

	public function updateFromArray($arr)
	{
		foreach ($arr as $prop=>$value)
		{
			if ($prop == 'password')
			{
				$this->newPassword($value);
			}
			elseif (in_array($prop, $this->getProperties()))
			{
				$this->$prop = $value;
			}
		}
		return($this->save());

	}

	public function newPassword($p)
	{
		$salt = Config::get('app.salt');
		$this->password = Hash::make($p . $salt);
	}

	public function permalink()
	{
		$url =action('UserController@showDetail', $this->id);
		$reputation_class="green";
		if ($this->reputation < 75) $reputation_class='yellow';
		if ($this->reputation < 50 ) $reputation_class='orange';
		if ($this->reputation < 25) $reputation_class='red';
		$link_text = $this->getPublicName() . ' <span class="reputation_icon mini '. $reputation_class . '">&nbsp;</span>';
		return '<a href="'. $url . '">' . $link_text . '</a>';
	}

	public function getPublicName()
	{
		return $this->first_name . ' ' . substr($this->last_name, 0, 1) . '.';
	}

	public function getName()
	{
		if (isAdmin() || Auth::user() == $this) return $this->first_name . ' ' . $this->last_name;
		else return $this->first_name . ' ' . substr($this->last_name, 0, 1) . '.';
	}

	public function getDetailLink()
	{
		return action('UserController@showDetail', $this->id);
	}

	public function getDeleteLink()
	{
		return action('UserController@doDelete', $this->id);
	}

	public function getEditLink()
	{
		return action('UserController@doEdit', $this->id);
	}

	public function getBlockLink()
	{
		return action('UserController@doBlock', $this->id);
	}

	public function getOwnerId()
	{
		// the "owner" of a user record is always that user
		return $this->id;
	}

	public function hasPermissionTo($action, $object)
	{
		global $permissions;

		// if this user is a site administrator, simply return True
		if ($this->role == 'administrator') return True;

		// if this user owns this object, say Yes!
		if ($this->id == $object->getOwnerId()) return True;

		// check this user's role against the permissions array
		if ($permissions[get_class($object)][$action][$this->role]) return True;

		return False;
	}

	public function getOrgs()
	{
		/* TODO */
	}

	public function isOrgAdmin()
	{
		/* TODO */
		/*
			Grab all organizational relationships where user_id = $this->id and relationship_type = 'admin'
			if yes, return true
		*/
		return False;
	}

}

