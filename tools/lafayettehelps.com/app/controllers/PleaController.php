<?php

class PleaController extends BaseController
{
	public function showDetail($id)
	{
		if (! $plea = Plea::find($id) ) debug('no plea found with that id');
		if (! Auth::check() ) App::abort(404);
		return View::make('plea.detail', array('plea' => $plea, 'author' => $plea->author));
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
			return Redirect::to(URL::previous());
		}
		if (Input::has('_token'))
		{
			return $this->create(Input::except('_token'));
		}
		return View::make('plea.edit', array('plea' => $plea));
	}

	public function create($pleadata)
	{
		// first, we create the plea object
		$plea = new Plea;
		
		// now we attempt to save it to the database with submitted data
		return $this->save($plea, $pleadata, $update_reputation = True);
	}

	public function save($plea, $pleadata, $update_reputation = False)
	{
		if($plea->validateAndUpdateFromArray($pleadata))
		{
			if ($update_reputation)
			{
				$plea->author->doHitReputation();
			}
			Session::put('status','success');
			Session::put('message', 'Saved!');
			return Redirect::route('pleadetail', array('id' => $plea->id));
		}
		else
		{
			err('entered data did not validate');
			Session::put('status','error');
			Session::put('message','entered data did not validate');
			Input::flash();
			if ($plea->id)
				return Redirect::route('pleaedit', array('id' => $plea->id))->withInput();
			else
				return Redirect::route('pleaadd')->withInput();
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
