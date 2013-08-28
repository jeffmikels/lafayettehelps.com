@extends('layout')

@section('content')
	@foreach($users as $user)
		<a href="{{ $user->getProfileLink() }}">{{ $user->first_name }}</a>
		<a href="{{ $user->getEditLink() }}">[EDIT]</a>
	@endforeach
@stop