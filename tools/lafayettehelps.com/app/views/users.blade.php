@extends('layout')

@section('content')
	@foreach($users as $user)
		<p>{{ $user->username }}</p>
		<?php debug($user); ?>
	@endforeach
@stop