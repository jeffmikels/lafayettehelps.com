@extends('layout')

@section('content')

<h2>Relationships</h2>

<table id="organizational_relationships">
	<thead>
		<tr>
			<th>Person</th>
			<th>Organization</th>
			<th>Relationship</th>
		</tr>
	</thead>
	<tbody>

		@foreach ($relationships as $relationship)
		<tr>
			<td><a href="{{ action('UserController@showDetail', $relationship['user']->id ) }}">{{ $relationship['user']->getName() }}</a></td>

			@if ($relationship['organization'] === NULL)
			<td>NONE</td>
			@else
			<td><a href="{{ action('OrganizationController@showDetail', $relationship['organization']->id ) }}">{{ $relationship['organization']->name }}</a></td>
			@endif

			<td>{{ $relationship['relationship_type'] }}</td>
		</tr>
		@endforeach

	</tbody>
</table>

<script type="text/javascript">
$(document).ready(function(){$('#organizational_relationships').tablesorter({sortList: [[0,0]] })});
</script>

@stop
