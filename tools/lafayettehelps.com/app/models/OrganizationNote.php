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
		return $this->belongsTo('Organization','organization_id');
	}

	public function creator()
	{
		return $this->belongsTo('User','contributed_by');
	}
}
