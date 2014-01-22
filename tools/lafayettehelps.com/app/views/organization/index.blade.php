@extends('layout')

@section('content')

	<a href="{{ action('UserController@doAdd') }}">[ADD USER]</a>

	<ul class="user_list">
	@foreach($users as $user)
		<li class="user_link">
			{{ $user->permalink() }}&nbsp;
			@if (isAdmin())
			<a href="{{ $user->getDeleteLink() }}">[DELETE]</a>
			@endif
			@if (isSelf($user) || isAdmin())
			<a href="{{ $user->getEditLink() }}">[EDIT]</a>
			@endif
		</li>
	@endforeach
	</ul>

@stop
