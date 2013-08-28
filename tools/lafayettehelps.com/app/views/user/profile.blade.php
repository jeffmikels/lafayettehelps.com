@extends('layout')

@section('content')
		<a href="{{ $user->getProfileLink() }}">{{ $user->first_name }}</a>
		<a href="{{ $user->getEditLink() }}">[EDIT]</a>
		<?php debug($user); ?>
@stop