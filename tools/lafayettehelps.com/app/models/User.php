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
	
	public function validateContent($prop, $content)
	{
		/* TODO finish form submission validation */
		return True;
	}

	public function validateAndCreateFromArray($arr)
	{
		foreach ($arr as $prop=>$value)
		{
			if (! $this->validateContent($prop, $value))
			{
				//debug($prop . " did not validate");
				return False;
			}
		}
		$this->updateFromArray($arr);
		return $this->id;
	}
	
	public function validateAndUpdateFromArray($arr)
	{
		foreach ($arr as $prop=>$value)
		{
			if (! $this->validateContent($prop, $value))
			{
				//debug($prop . " did not validate");
				return False;
			}
		}
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
				$this->password = hash('sha256', hash('sha256', $value) . hash('sha256','it is always important to use a salt for your password hashing'));
			}
			elseif (isset($this->$prop))
			{
				$this->$prop = $value;
			}
		}
		return($this->save());
		
	}

	public function getProfileLink()
	{
		return action('UserController@showProfile', $this->id);
	}

	public function getEditLink()
	{
		return action('UserController@editUser', $this->id);
	}

	public function getOwnerId()
	{
		// the "owner" of a user record is always that user
		return $this->id;
	}
	
	public function hasPermissionTo($action, $object)
	{
		global $permissions;
		// check this user's role against the permissions array
		if ($permissions[get_class($object)][$action][$this->role]) return True;
		
		if ($this->role == 'administrator') return True;
		
		// if this user owns this object, say Yes!
		if ($this->id == $object->getOwnerId()) return True;
		
		return False;
	}

}

