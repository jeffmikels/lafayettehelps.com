<?php

class Offer extends Eloquent
{
	protected $table = 'offers';
	protected $properties = array('id','user_id','summary','details','dollars','alternatives','deadline');
	protected $public_properties = array('summary','details','dollars','alternatives','deadline');

	public function author()
	{
		return $this->belongsTo('User');
	}

	public function comments()
	{
		return $this->morphMany('Comment', 'commentable');
	}

	public function getProperties()
	{
		return $this->properties;
	}

	public function getPublicProperties()
	{
		return $this->public_properties;
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

}
