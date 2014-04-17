@extends('layout')

@section('content')
	<?php
	$gravatar_link = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($user->email))) . ".jpg?s=100";
	?>


	<div class="well">
		@if (isAdmin() || isOrgAdmin())
		<img class="gravatar thumbnail pull-right" src="{{$gravatar_link}}" />
		@endif
		<h2>
			<a href="{{ $user->getDetailLink() }}">{{ $user->getPublicName() }}</a>
			@if ( me()->hasPermissionTo('edit', $user) )
			<small><a class="btn btn-info" href="{{ $user->getEditLink() }}">EDIT</a></small>
			@endif
			@if ( me()->hasPermissionTo('delete', $user) )
			<small><a class="btn btn-info" href="{{ route('userdelete', array('id' => $user->id)) }}" onclick="return doconfirm('Are you sure you want to delete this user?');">DELETE</a></small>
			@endif
			@if ( me()->hasPermissionTo('contact', $user))
			<small><a class="btn btn-info" href="{{route('contact', array('object_type' => 'user', 'id' => $user->id ))}}">Contact {{$user->getName()}}</a></small>
			@endif
			@if (me()->hasPermissionTo('recommend', $user))
			<small><a class="btn btn-info" href="{{route('recommendationadd', array('user_id' => $user->id )) }}">Write Recommendation</a></small>
			@endif
		</h2>

		<h2>Reputation</h2>
		<?php show_reputation($user); ?>
	</div>

	@if(count($user->activePleas()))
	<div class="panel panel-primary">
		<div class="panel-heading">
			Active Requests
		</div>
		<div class="panel-body">
			<div class="list-group plea-list">

			@foreach ($user->activePleas() as $plea)

				<a class="list-group-item" href="{{ action('PleaController@showDetail', $plea->id) }}">
					<h2>{{ $plea->summary }}</h2>
					<div class="meta">
						<span class="meta-item date">{{$plea->updated_at->toDayDateTimeString()}}</span>
						<span class="meta-item author">{{$plea->author->getName()}}<?php show_mini_reputation($plea->author); ?></span>
						<span class="meta-item money">
							Money Requested:
							@if (floatval($plea->dollars) > 0)
								${{ floatval($plea->dollars) }}
							@else
								None
							@endif
						</span>
						<span class="meta-item pledged">
							Money Pledged: ${{$plea->totalPledged()}}
						</span>
						@if ($plea->deadline)
							<span class="meta-item deadline">
							Deadline: {{$plea->deadline}}
							</span>
						@endif
					</div>
				</a>

			@endforeach
			</div>
		</div>
	</div>
	@endif

	@if (isOrgAdmin() || isAdmin())
	<div class="alert alert-danger">
		You are an administrator, so you see extra information on people's profile pages.
		<strong>However, nothing below here will ever show up for non-administrators.</strong>
	</div>
	@endif

	@if (isAdmin())
		<div class="panel panel-danger">
			<div class="panel-heading">For Administrators</div>
			<div class="panel-body">
				<small>You have been granted administrative access to this site. Please use your power with caution.</small>
				<table class="table">
					<tr><td><strong>Full Name:</strong></td><td>{{$user->getName()}}</td></tr>
					<tr><td><strong>Phone:</strong></td><td>{{$user->phone}}</td></tr>
					<tr><td><strong>City:</strong></td><td>{{$user->city}}</td></tr>
					<tr><td><strong>Status</strong></td><td>{{$user->status}}</td></tr>
					<tr><td><strong>Role</strong></td><td>{{$user->role}}</td></tr>
				</table>
			</div>
		</div>
	@endif


	@if (isOrgAdmin() || isAdmin())
	<div class="panel panel-warning">
		<div class="panel-heading">
			For Organization Administrators
		</div>
		<div class="panel-body">
			<div class="panel panel-info">
				<div class="panel-heading">
					Organizational Relationships
				</div>
				
				<div class="panel-body">
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
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-heading">
					Organization Notes
				</div>
				<div class="panel-body">
					<div class="notes">
						@foreach ($user->notes as $note)
						<div class="note well">
							<div class="note-meta">
								Posted by {{$note->creator->getName()}} on {{$note->created_at->toDayDateTimeString()}} for {{$note->organization->name}}
							</div>
							<div class="note-body">
								{{$note->body}}
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
			
			@if (count($user->uncompletedPledges()))
			<div class="panel panel-primary">
				<div class="panel-heading">
					Uncompleted Pledges
				</div>
				<div class="panel-body">
					<div class="list-group plea-list">

					@foreach ($user->uncompletedPledges() as $pledge)
						<a class="list-group-item" href="{{ action('PleaController@showDetail', $pledge->plea_id) }}">
							<p><strong>Request Summary:</strong> {{ $pledge->plea->summary }}</p>
							@if ($pledge->dollars > 0)
								<p><strong>Dollars:</strong> {{ $pledge->dollars }}</p>
							@endif
							@if ($pledge->alternatives != '')
								<p><strong>Alternatives:</strong> {{ $pledge->alternatives }}</p>
							@endif
						</a>
					@endforeach

					</div>
				</div>
			</div>
			@endif

			@if(count($user->recommendationsReceived))
			<div class="panel panel-primary">
				<div class="panel-heading">
					Recommendations Received
				</div>
				<div class="panel-body">
					@foreach ($user->recommendationsReceived as $rec)
					<div class="well">
						<p>{{$rec->body}}</p>
						contributed by {{$rec->contributedBy->permalink()}}
					</div>
					@endforeach
				</div>
			</div>
			@endif

			@if(count($user->recommendationsGiven))
			<div class="panel panel-primary">
				<div class="panel-heading">
					Recommendations Given
				</div>
				<div class="panel-body">
					@foreach ($user->recommendationsGiven as $rec)
					<div class="well">
						<p>{{$rec->body}}</p>
						contributed by {{$rec->contributedFor->permalink()}}
					</div>
					@endforeach
				</div>
			</div>
			@endif
			
		</div>
	</div>

	
	@endif

@stop
