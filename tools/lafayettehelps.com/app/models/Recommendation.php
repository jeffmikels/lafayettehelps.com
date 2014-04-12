<?php
	
class Recommendation extends Eloquent
{
	protected $table = 'recommendations';
	protected $softDelete = true;
	
	public function contributedBy()
	{
		return $this->belongsTo('User', 'contributed_by', 'id');
	}
	
	public function contributedFor()
	{
		return $this->belongsTo('User', 'contributed_for','id');
	}
	
	public function validateAndUpdateFromArray($array)
	{		
		$rules = array(
			'contributed_for' => 'required|exists:users,id',
			'body' => 'required'
		);
		$validator = Validator::make($array, $rules);
	    if ($validator->fails())
	    {
			err('Something was wrong with the recommendation you submitted. Check below.');
			return Response::json($validator->failed());
	        return Redirect::to(URL::previous())->withErrors($validator);
	    }
		$this->contributed_for = $array['contributed_for'];
		$this->body = $array['body'];
		$this->contributed_by = me()->id;
		$this->save();
		
		//Boost the reputation of the person who just received the recommendation
		$this->contributedFor->doHelpReputation(10);
		
		return Redirect::route('userprofile', array('id'=>$array['contributed_for']));			
	}
}