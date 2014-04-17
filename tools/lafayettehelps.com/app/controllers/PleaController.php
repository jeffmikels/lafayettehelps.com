<?php

class PleaController extends BaseController
{
	public function showDetail($id)
	{
		if (! $plea = Plea::find($id) )
		{
			msg('You clicked on an invalid link.');
			return Redirect::route('pleas');
		}
		// Allow Pleas to be viewed by non-logged in users.
		//if (! Auth::check() ) App::abort(404);
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
	public function reject($id)
	{
		$plea = Plea::find($id);
		// check privileges
		if (! me()->hasPermissionTo('reject', $plea))
		{
			err('You do not have permission to reject requests for help.');
			return Redirect::to(URL::previous());
		}

		$reason = Input::get('reason','NO REASON GIVEN.');
		if ($reason == '') $reason = 'NO REASON GIVEN.';

		$default_message = <<<EOF
<p>lafayettehelps.com depends on the integrity and honesty of its users and the validity of the requests that are posted. We intend for it to be a supportive community of people who truly care for each other and are eager to have healthy relationships with each other. However, a request connected to you doesn't fit within our standards of a healthy community, and so one of our administrators has removed it.</p>

<h3>The Request:</h3>

<p>%s</p>


<p>This wasn't done by an automatic computer process. An actual human being decided to remove your request, and the reasons for doing so are here:</p>

<p>%s</p>

<p>If you think this was done in error, simply reply to this email message and we will reconsider this action.

<hr />
<h4>request_id: %s</h4>
EOF;

		$message = sprintf($default_message, $plea->summary, $reason, $plea->id);


		// softDelete this Plea and email the author.
		$plea->delete();
		msg('Request has been sent to the trash');

		// send email message to author and everyone who made a pledge toward that request
		email($plea->author, me(), "Your request was removed.", $message);

		foreach ($plea->pledges as $pledge)
			email($pledge->author, me(), "A request you pledged to help was removed.", $message);

		return Redirect::route('pleas');
	}
}
