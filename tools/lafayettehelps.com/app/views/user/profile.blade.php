@extends('layout')

@section('content')
	<h2><a href="{{ $user->getProfileLink() }}">{{ $user->getName() }}</a>
	@if ( hasPermissionTo('edit', $user) )
	<small><a href="{{ $user->getEditLink() }}">[EDIT]</a></small>
	@endif
	</h2>

	<h2>Reputation</h2>

	<?php $reputation_class="green"; ?>
	<?php if ($user->reputation < 75) $reputation_class='yellow'; ?>
	<?php if ($user->reputation < 50 ) $reputation_class='orange'; ?>
	<?php if ($user->reputation < 25) $reputation_class='red'; ?>
	<div class="reputaton_bar" style="width:100%;box-sizing:border-box;border:1px solid #777;border-radius:3px;overflow:hidden;">
		<div class="reputation_color {{$reputation_class}}" style="width:{{ ($user->reputation) }}%;background-color:{{$reputation_class}};">&nbsp;</div>
	</div>

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
	<h2>Organizational Details</h2>
	<h3>Relationships</h3>
	<div class="relationships">
		TODO: Organizational relationships go here.
	</div>
	<h3>Notes</h3>
	<div class="notes">
		TODO: Organizational notes go here.
	</div>
	@endif

	<h2>Active Requests</h2>
	Past Requests Go Here :: (link) request title, status, deadline, progress

	<h2>Recent History</h2>
	<li>"USER" posted a request (link) title
	<li>"USER" made a pledge
	<li>"USER" fulfilled a pledge
	<li>"USER" recommended "OTHER USER"

	<h2>Recommendations</h2>
	most recent recommendations for this user go here


	<h3>User Debug</h3>
	<?php debug($user['attributes']); ?>

@stop