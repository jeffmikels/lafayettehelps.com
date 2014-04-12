<?php
class Pledge extends Eloquent
{
	protected $table = 'pledges';
	protected $softDelete = true;
	
	
	
	public function author()
	{
		return $this->belongsTo('User','user_id');
	}
	
	public function plea()
	{
		return $this->belongsTo('Plea','plea_id');
	}
	
	public function validateAndUpdateFromInput()
	{
		if (! me()->hasPermissionTo('add','pledge'))
		{
			err('You don\'t have permission to add pledges');
			return Redirect::to(URL::previous());
		}
		$rules = array(
			'plea_id'=>'required|exists:pleas,id',
			'dollars'=>'numeric|required_without:alternatives',
			'alternatives'=>'required_without:dollars'
		);

		$validator = Validator::make(Input::all(), $rules);
		
	    if ($validator->fails())
	    {
			err('Something was wrong with the pledge you submitted. Check below.');
			// return Response::json($validator->failed());
	        return Redirect::to(URL::previous())->withErrors($validator);
	    }
		
		$this->plea_id = Input::get('plea_id');
		$this->dollars = Input::get('dollars', '');
		$this->alternatives = Input::get('alternatives', '');
		$this->user_id = me()->id;
		$this->save();
		return Redirect::to(URL::previous());
		
	}
}