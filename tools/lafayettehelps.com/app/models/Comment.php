<?php

// comments are associated with pleas to encourage discussion

class Comment extends Eloquent
{
	protected $table = 'comments';
	protected $status_options = array('unapproved' => 'Unapproved','approved' => 'Approved');
	protected $properties = array('commentable_type','commentable_id','user_id','comment','status');

	public function author()
	{
		return $this->belongsTo('User', 'user_id');
		//return $this->belongsToMany('User','relationships','organization_id','user_id')->withPivot('relationship_type');
	}

	public function commentable()
	{
		return $this->morphTo();
	}

	public function getProperties()
	{
		return $this->properties;
	}

	public function updateFromArray($arr)
	{
		foreach($this->properties as $prop)
		{
			if (isset($arr[$prop])) $this->$prop = $arr[$prop];
		}
		// default to unapproved status if new
		if ( ! isset($this->id) ) $this->status = $this->getInitialStatus();
		return $this->save();
	}

	public function getInitialStatus()
	{
		$user = User::find( $this->user_id );
		if ($user->role == 'user' || $user->status == 'unverified') return 'unapproved';
		else return 'approved';
	}

}
