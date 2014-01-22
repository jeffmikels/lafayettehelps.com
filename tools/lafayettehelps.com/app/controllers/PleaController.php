<?php

class PleaController extends BaseController
{
	public function showDetail($id)
	{
		if (! $plea = Plea::find($id) ) debug('no plea found with that id');
		if (! Auth::check() ) App::abort(404);
		return View::make('plea.detail', array('plea' => $plea, 'author' => $plea->author));
	}

	public function showPleasByUser($user_id)
	{
		return not_implemented();
	}

	public function add()
	{
		// adding pleas is subject to moderation
		if (! Auth::check() )
		{
			msg('Start your request by creating an account or logging in.');
			return Redirect::route('login');
		}
		$plea = new Plea;
		if ( ! me()->hasPermissionTo('add', $plea))
		{
			Session::put('status','error');
			Session::put('message', 'You do not have permission to add pleas!');
			return Redirect::to('not-allowed');
		}
		if (Input::has('_token'))
		{
			return $this->create(array_except(Input::all(),'_token'));
		}
		return View::make('plea.edit', array('plea' => $plea));
	}

	public function create($pleadata)
	{
		$org = new Plea;
		return $this->save($org, $pleadata);
	}

	public function save($plea, $pleadata)
	{
		if($plea->validateAndUpdateFromArray($pleadata))
		{
			Session::put('status','success');
			Session::put('message', 'Saved!');
			return Redirect::to('plea/'. $plea->id);
		}
		else
		{
			Session::put('status','error');
			Session::put('message','entered data did not validate');
			Input::flash();
			return Redirect::to('plea/'. $plea->id . '/edit')->withInput();
		}
	}

	public function edit($id)
	{
		$plea = Plea::find($id);
		if ( ! Auth::user()->hasPermissionTo('edit',$plea))
		{
			Session::put('status','error');
			Session::put('message', 'You do not have permission to edit this plea!');
			return Redirect::to('plea/'. $plea->id);
		}
		elseif (Input::has('_token'))
		{
			return $this->save($plea, array_except(Input::all(), '_token'));
		}
		else
		{
			return View::make('plea.edit', array('plea' => $plea));
		}
	}
}
