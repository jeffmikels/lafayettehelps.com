@extends('layout')

@section('content')

	<h1>Current Users</h1>

	<div class="list-group user-list">
	
		@foreach($users as $user)
		<a class="list-group-item" href="{{ action('UserController@showDetail', $user->id); }}">
			<h2>{{$user->getPublicName()}}</h2>
			{{$user->showMiniReputation()}}
		</a>
		@endforeach

	</div>
	
	{{ $users->links() }}
@stop
