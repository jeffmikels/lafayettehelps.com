@extends('layout')

@section('content')
	
	<a href="{{ action('UserController@addUser') }}">[ADD USER]</a>
	
	<ul class="user_list">
	@foreach($users as $user)
		<li class="user_link"><a href="{{ $user->getProfileLink() }}">{{ $user->first_name }}</a>&nbsp;<a href="{{ $user->getEditLink() }}">[EDIT]</a></li>
	@endforeach
	</ul>

@stop