<?php
class Organization extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'organizations';
	protected $properties = Array('id','name','email','phone','address','city','state','zip','status','verified_by');
	protected $public_properties = Array('name','email','phone','address','city','state','zip');
	protected $status_options = Array('unverified' => 'Unverified', 'verified' => 'Verified');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('');
	protected $softDelete = true;

	public function relationships()
	{
		//return $this->hasMany('Relationship');
		return $this->belongsToMany('User','relationships','organization_id','user_id')->withPivot('relationship_type');
	}

	public function admins()
	{
		return $this->relationships()->where('relationship_type', 'admin')->get();
	}

	public function getOwnerId()
	{
		return $this->verified_by;
	}

	public function getRelationshipsByType()
	{
		$retval = Array();
		foreach ($this->relationships as $relationship)
		{
			$type = $relationship->pivot->relationship_type;
			if (! array_key_exists($type,$retval)) $retval[$type] = Array();
			$retval[$type][] = $relationship;
		}
		return $retval;
	}

	public function getProperties()
	{
		return $this->properties;
	}
	public function getPublicProperties()
	{
		return $this->public_properties;
	}
	
	public function getStatusOptions()
	{
		return $this->status_options;
	}
	public function validateContent($prop, $content)
	{
		$validates = false;
		$property_exists = false;
		$content_matches = false;

		if (in_array($needle = $prop, $haystack = $this->getProperties())) $property_exists = true;
		/* for now, we assume the content matches */

		$content_matches = true;
		return ($property_exists && $content_matches);
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
		$new_organization = false;
		if ( ! $this->id )
		{
			$new_organization = true;
		}
		foreach ($arr as $prop=>$value)
		{
			$this->$prop = $value;
		}

		// mark the current user as the admin contact for this organization
		if ($new_organization)
		{
			$id = $this->save();
			if ($id) $this->makeAdmin(Auth::user()->id);
			return ($id);
		}
		else return($this->save());
	}

	public function makeAdmin($user_id)
	{
		return $this->relationships()->attach($user_id, array('relationship_type'=>'admin'));
	}
	
	public function makeMember($user_id)
	{
		return $this->relationships()->attach($user_id, array('relationship_type'=>'member'));		
	}
	
	public function removeRelationship($user_id)
	{
		return $this->relationships()->detach($user_id);
	}

	public function getDetailLink()
	{
		return action('OrganizationController@showDetail', $this->id);
	}

	public function getEditLink()
	{
		return action('OrganizationController@edit', $this->id);
	}

	public function getAdminIds()
	{
		// grab admin ids from the organization / user relationship table
		// return an array of user ids
		//return $this->id;
	}
}

