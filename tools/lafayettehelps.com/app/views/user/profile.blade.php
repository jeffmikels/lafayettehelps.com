@extends('layout')

@section('content')
	<?php
	$gravatar_link = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($user->email))) . ".jpg?s=100";
	?>
	@if (me() == $user)
	<div class="alert alert-info">MY ACCOUNT</div>
	@endif


	<div class="well">
		<img class="gravatar thumbnail pull-right" src="{{$gravatar_link}}" />
		<h2><a href="{{ $user->getDetailLink() }}">{{ $user->getName() }}</a>
		@if ( me()->hasPermissionTo('edit', $user) )
		<small><a class="btn btn-info" href="{{ $user->getEditLink() }}">EDIT</a></small>
		@endif
		@if ( me()->hasPermissionTo('delete', $user) )
		<small><a class="btn btn-info" href="{{ route('userdelete', array('id' => $user->id)) }}" onclick="return doconfirm('Are you sure you want to delete this user?');">DELETE</a></small>
		@endif
		</h2>

		<h2>Reputation</h2>

		<?php show_reputation($user); ?>
	</div>

	<h2>Contact</h2>
	<div class="contact-form">
		{{Form::open(array('route'=>'contact', 'role'=>'form'))}}
		{{Form::hidden('email', $user->email)}}
		<div class="form-group">
			{{Form::label('content', 'Email Content')}}
			{{Form::textarea('content', NULL, array('class'=>'form-control'))}}			
		</div>
		<div class="form-group">
			{{Form::submit('Send Email', array('class'=>'btn btn-primary form-control'))}}
		</div>
		{{Form::close()}}
	</div>
	
	<div class="panel panel-info">
		<div class="panel-heading">Contact Details</div>
		<div class="panel-body">
			<table class="table">
				<tr><td><strong>Phone:</strong></td><td>{{$user->phone}}</td></tr>
				<tr><td><strong>City:</strong></td><td>{{$user->city}}</td></tr>
			</table>
		</div>
	</div>

	@if (isAdmin())
	<div class="panel panel-info">
		<div class="panel-heading">For Administrators</div>
		<div class="panel-body">
			<small>You have been granted administrative access to this site. Please use your power with caution.</small>
			<table class="table">
				<tr><td><strong>Status</strong></td><td>{{$user->status}}</td></tr>
				<tr><td><strong>Role</strong></td><td>{{$user->role}}</td></tr>
			</table>
		</div>
	</div>
	@endif


	@if (isOrgAdmin() || isAdmin())
	<div class="panel panel-warning">
		<div class="panel-heading">
			<h3>Organizational Connections</h3>
		</div>
		<div class="panel-body">
			<h3>Relationships</h3>
			<table class="relationships table">
				<tr>
					<th>Organization</th><th>Relationship</th>
				</tr>

				@foreach ($user->organizations as $organization)
				<tr>
					<td><a href="{{ action('OrganizationController@showDetail', $organization->id) }}">{{ $organization->name }}</a></td>
					<td>{{ $organization->pivot->relationship_type }}</td>
				</tr>
				@endforeach

			</table>
			<h3>Notes</h3>
			<div class="notes">
				@foreach ($user->notes as $note)
				<div class="panel panel-info">
					<div class="panel-heading">
						Posted by {{$note->creator->getName()}} for {{$note->organization->name}}
					</div>
					<div class="panel-body">
						{{$note->body}}
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	@endif


	@if (isAdmin())
	<h3>User Debug</h3>
	<?php debug($user['attributes']); ?>
	@endif



	<div class="panel panel-primary">
		<div class="panel-heading">
		<h3>Active Requests</h3>
		</div>
		<div class="panel-body">
		<p>Past Requests Go Here :: (link) request title, status, deadline, progress</p>
		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Recent History</h3>
		</div>
		<div class="panel-body">
			<ul>
			<li>"USER" posted a request (link) title
			<li>"USER" made a pledge
			<li>"USER" fulfilled a pledge
			<li>"USER" recommended "OTHER USER"
			</ul>
		</div>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3>Recommendations</h3>
		</div>
		<div class="panel-body">
			most recent recommendations for this user go here
		</div>
	</div>


@stop
