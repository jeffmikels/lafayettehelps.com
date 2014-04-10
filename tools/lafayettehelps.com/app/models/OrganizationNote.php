<?php

class OrganizationNote extends Eloquent
{
	protected $table = 'organization_notes';
	protected $softDelete = true;
	
	public function __construct()
	{
		$this->contributed_by = me()->id;
	}
	
	public function organization()
	{
		return $this->hasOne('Organization','id','organization_id');
	}
	
	public function creator()
	{
		return $this->hasOne('User','id','contributed_by');
	}
}
