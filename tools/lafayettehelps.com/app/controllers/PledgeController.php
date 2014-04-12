<?php

class PledgeController extends BaseController
{	
	public function showDetail($id)
	{}

	public function add()
	{
		$pledge = new Pledge;
		return $pledge->validateAndUpdateFromInput();
	}

	public function doEdit($id)
	{}

	public function doDelete($id)
	{}
	
	public function showPledgesByUser($user_id)
	{
		return not_implemented();
	}

}
