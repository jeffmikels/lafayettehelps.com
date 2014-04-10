@extends('layout')

@section('content')
	@if ($organization->status == 'unverified')
	<div class="alert alert-danger">unverified</div>
	@endif

	<div class="well">
		<h2><a href="{{ $organization->getDetailLink() }}">{{ $organization->name }}</a>
		@if ( me()->hasPermissionTo('edit', $organization) )
		<small><a class="btn btn-info" href="{{ $organization->getEditLink() }}">EDIT</a></small>
		@endif
		</h2>
	</div>

	<h2>Contact</h2>

	<div class="contact-form">
		{{Form::open(array('route'=>'contact', 'role'=>'form'))}}
		{{Form::hidden('email', $organization->email)}}
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
		<div class="panel-heading">Organization Details</div>
		<div class="panel-body">
			<table class="table">
				@foreach ( $organization->getPublicProperties() as $prop)
				<tr><td><strong>{{$prop}}</strong></td><td>{{$organization->$prop}}</td></tr>
				@endforeach
			</table>
		</div>
	</div>

	@if (me()->isOrgAdmin($organization) || isAdmin())
	<h2>For Administrators</h2>
	<small>You have been granted administrative access to this site. Please use your power with caution.</small>
	<div class="panel panel-info">
		<div class="panel-heading">Relationships</div>
		<div class="panel-body">
			<div class="admin_details">
				<table class="table">
					<tr>
						<th>Contact</th><th>Relationship</th>
					</tr>

					@foreach ($relationships as $relationship)
					<tr>
						<td><a href="{{ action('UserController@showDetail', $relationship->id) }}">{{ $relationship->first_name }} {{ $relationship->last_name }}</a></td>
						<td>{{ $relationship->pivot->relationship_type }}</td>
					</tr>
					@endforeach

				</table>
			</div>
		</div>
	</div>
	@endif

@stop