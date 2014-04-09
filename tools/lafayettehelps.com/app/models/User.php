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
	
	// enable soft deletes
	protected $softDelete = true;
	
	// options arrays are created like so:
	// the key is what gets stored in the database
	// the value is what we use for display
	protected $role_options = Array('user' => 'User', 'moderator' => 'Moderator', 'editor' => 'Editor', 'administrator' => 'Administrator');
	protected $status_options = Array('unverified' => 'Unverified','verified' => 'Verified', 'blocked'=>'Blocked');
	protected $properties = Array('id','username','password','email','first_name','last_name','phone','address','city','state','zip','reputation','status','role');
	protected $public_properties = Array('username','first_name','phone','reputation');

	// default property values
//   public $reputation = 75;
//  	public $status = 'unverified';
// 	public $role = 'user';


	public function __construct()
 	{
 		if (! $this->reputation) $this->reputation = 75;
 		if (! $this->status) $this->status = 'unverified';
 		if (! $this->role) $this->role = 'user';
 	}

	// some properties should never be updated by the web form
	// we set their validations to "refuse"
	protected $validations = Array(
		'default' => '#^[\d\w\s\.]{1,64}$#',
		'username' => '#^[\d\w\.]{1,32}$#',
		'email' => '#^[a-zA-Z0-9.!\#$%&\'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$#',
		'phone' => '#^[ 0-9()\-]+$#',
		'password' => '#........+#',
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

		$pattern = $this->validations['default'];
		if (isset($this->validations[$prop])) $pattern = $this->validations[$prop];
		if ($pattern == 'refuse') return false;
		$content_matches = preg_match($pattern, $content);

		//print "<p>$prop :: $content :: $pattern :: $content_matches</p>";
		return ($property_exists && $content_matches);
	}


	public function validateAndUpdateFromArray($arr)
	{
		// check each property value against the validation regexes defined above
		foreach ($arr as $prop=>$value)
		{
			$failed = Array();
			if ($value && (! $this->validateContent($prop, $value)))
			{
				//debug($prop . " did not validate");
				$failed[$prop] = "$value is an invalid option for $prop";
			}
		}

		// perform special checks for passwords


		// but leaving the password unchanged
		// if there is no id set, then we are creating a new user and must have a password
		if (! isset($this->id) and ! (isset($arr['password']) and $arr['password']))
		{
			$failed['password'] = "You must have a password when you are registering a new user";
		}

		// if there is something in the password field, the password_confirm field must equal it.
		if (isset($arr['password']) and $arr['password'])
		{
			if (! isset($arr['password_confirm']) and ! $arr['password_confirm'] === $arr['password_confirm'])
			{
				$failed['password_confirm'] = 'Password fields did not match';
			}
		}

		if ($failed)
		{
			Session::flash('validation_failures', $failed);
			err('There\'s a problem with the information you entered. Please double-check it below.');
			return False;
		}

		unset($arr['password_confirm']);


		// print "preparing to save new user";
		// dd($arr);

		if ($this->updateFromArray($arr))
			return $this->id;
		else
		{
			err('I had an error registering you. Perhaps someone has already registered using that username.');
			return False;
		}

	}
	
	public function updateFromArray($arr)
	{
		foreach ($arr as $prop=>$value)
		{
			if ($prop == 'password')
			{
				// remember that we only make the hash when saving the password to the database
				// Laravel automatically uses the Hashing function in the Auth::attempt method
				$this->setPassword($value);
			}
			elseif (in_array($prop, $this->getProperties()))
			{
				$this->$prop = $value;
			}
		}
		return($this->save());
	}
	
	public function setPassword($pw)
	{
		$this->password = $this->newPassword($pw);
	}
	
	public function newPassword($pw)
	{
		return Hash::make(saltPassword($pw));
	}

	public function permalink()
	{
		$url = action('UserController@showDetail', $this->id);
		$reputation_class="green";
		if ($this->reputation < 75) $reputation_class='yellow';
		if ($this->reputation < 50 ) $reputation_class='orange';
		if ($this->reputation < 25) $reputation_class='red';
		$link_text = $this->getPublicName() . ' <span class="reputation_icon mini '. $reputation_class . '">&nbsp;</span>';
		return '<a href="'. $url . '">' . $link_text . '</a>';
	}
	
	public function showReputation()
	{
		show_reputation($this);
	}
	
	public function showMiniReputation()
	{
		show_mini_reputation($this);
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
		if ($this->id && $this->id == $object->getOwnerId()) return True;

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

