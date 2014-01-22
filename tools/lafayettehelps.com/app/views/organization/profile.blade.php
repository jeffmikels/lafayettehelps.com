@extends('layout')

@section('content')
	<h2><a href="{{ $organization->getDetailLink() }}">{{ $organization->name }}</a>
	@if ( hasPermissionTo('edit', $organization) )
	<small><a href="{{ $organization->getEditLink() }}">[EDIT]</a></small>
	@endif
	</h2>

	<h2>Details</h2>

	<div class="contact">
	Contact Form Goes Here
	</div>

	@foreach ( $organization->getPublicProperties() as $prop)
	<div class="{{$prop}}">{{$prop}} :: {{$organization->$prop}}</div>
	@endforeach

	@if (isAdmin())
	<h2>Administrative Details</h2>
	<small>You have been granted administrative access to this site. Please use your power with caution.</small>
	<div class="admin_details">

		<h3>Organizational Relationships</h3>
		<!-- NEED A SORTABLE TABLE FOR RELATIONSHIPS -->
		<table>
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

@stop