<?php

class OrganizationController extends BaseController
{
	public function getIndex()
	{
		if(isAdmin()) $orgs = Organization::orderby('name')->paginate(30);
		else $orgs = Organization::orderby('name')->where('status','=','verified')->paginate(30);
		return View::make('organization.index', array('organizations' => $orgs));

		//return View::make('organization.index')
	}

	public function showDetail($id)
	{
		if (! $organization = Organization::find($id) ) App::abort(404);
		if (isAdmin()) return View::make('organization.profile', array('organization' => $organization, 'relationships' => $organization->relationships));
		else return View::make('organization.profile', array('organization' => $organization));
	}

	public function add()
	{
		// adding organizations is subject to moderation
		$organization = new Organization();
		if ( ! Auth::user()->hasPermissionTo('add',$organization))
		{
			Session::put('status','error');
			Session::put('message', 'You do not have permission to add organizations!');
			return Redirect::route('home');
		}
		if (Input::has('_token'))
		{
			return $this->create(array_except(Input::all(),'_token'));
		}
		return View::make('organization.edit', array('organization' => $organization));
	}

	public function create($orgdata)
	{
		$org = new Organization();
		return $this->save($org, $orgdata);
	}

	public function save($organization, $orgdata)
	{
		if($organization->validateAndUpdateFromArray($orgdata))
		{
			Session::put('status','success');
			Session::put('message', 'Saved!');
			return Redirect::to('organization/'. $organization->id);
		}
		else
		{
			Session::put('status','error');
			Session::put('message','entered data did not validate');
			Input::flash();
			return Redirect::to('organization/'. $organization->id . '/edit')->withInput();
		}
	}

	public function edit($id)
	{
		$organization = Organization::find($id);
		if ( ! Auth::user()->hasPermissionTo('edit',$organization))
		{
			Session::put('status','error');
			Session::put('message', 'You do not have permission to edit this organization!');
			return Redirect::to('organization/'. $organization->id);
		}
		elseif (Input::has('_token'))
		{
			return $this->save($organization, array_except(Input::all(), '_token'));
		}
		else
		{
			return View::make('organization.edit', array('organization' => $organization));
		}
	}
}
