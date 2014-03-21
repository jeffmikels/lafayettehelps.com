@extends('layout')

@section('content')
	<h2><a href="{{ $user->getDetailLink() }}">{{ $user->getName() }}</a>
	@if ( me()->hasPermissionTo('edit', $user) )
	<small><a href="{{ $user->getEditLink() }}">[EDIT]</a></small>
	@endif
	</h2>

	<h2>Reputation</h2>

	<?php show_reputation($user); ?>

	<h2>Details</h2>

	<div class="contact">
	Contact Form Goes Here
	</div>

	<div class="phone">
	Phone :: {{ $user->phone }}
	</div>

	<div class="city">
	City :: {{ $user->city }}
	</div>

	@if (isAdmin())
	<h2>Administrative Details<br />
	<small>You have been granted administrative access to this site. Please use your power with caution.</small>
	</h2>
	<div class="admin_details">
		<div class="status">
			Status :: {{$user->status}}
		</div>
		<div class="role">
			Role :: {{$user->role}}
		</div>
	</div>
	@endif


	@if (isOrgAdmin() || isAdmin())
	<div class="panel panel-warning">
		<div class="panel-heading">
			<h2>Organizational Details</h2>
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
				TODO: Organizational notes go here.
			</div>
		</div>
	</div>
	@endif



	<h2>Active Requests</h2>
	Past Requests Go Here :: (link) request title, status, deadline, progress

	<h2>Recent History</h2>
	<p>"History" items go here. A history item looks like this...</p>
	<ul>
	<li>"USER" posted a request (link) title
	<li>"USER" made a pledge
	<li>"USER" fulfilled a pledge
	<li>"USER" recommended "OTHER USER"
	</ul>
	<h2>Recommendations</h2>
	most recent recommendations for this user go here


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
