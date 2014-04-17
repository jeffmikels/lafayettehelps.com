<?php

class RecommendationController extends BaseController
{
	public function showDetail($id)
	{}

	public function showForm($user_id)
	{
		if (! me()->hasPermissionTo('recommend',User::find($user_id)))
		{
			if (me()->id == $user_id)
				err('You don\'t have permission to leave recommendations for yourself.');
			else
				err('You don\'t have permission to leave a recommendation for that user.');
			return Redirect::to(URL::previous());
		}
		$user = User::find($user_id);
		return View::make('recommend', array('user' => $user) );
	}

	public function doAdd($user_id)
	{
		if (! me()->hasPermissionTo('add','Recommendation'))
		{
			err('You don\'t have permission to leave a recommendation for that user.');
			return Redirect::to(URL::previous());
		}
		$recommendation = new Recommendation;
		return $recommendation->validateAndUpdateFromArray(Input::all());
	}

	public function doEdit($id)
	{}

	public function doDelete($id)
	{}

	public function showRecommendationsForUser($user_id)
	{
		return not_implemented();
	}

	public function showRecommendationsByUser($user_id)
	{
		return not_implemented();
	}


}
