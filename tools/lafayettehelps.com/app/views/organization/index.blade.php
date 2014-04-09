@extends('layout')

@section('content')


	<?php show_search_bar('organization'); ?>

	<h1>All Organizations</h1>

	<div class="list-group user-list">

		@foreach($organizations as $org)
		<a class="list-group-item" href="{{ action('OrganizationController@showDetail', $org->id); }}">
			@if (isAdmin())
			<span class="badge pull-right">{{$org->status}}</span>
			@endif
			<h2>{{$org->name}}</h2>
		</a>
		@endforeach

	</div>

	{{ $organizations->links() }}
@stop
