@extends('layout')

@section('content')
		<p>Edit User #{{ $user->id }}</p>

		<!--
		<?php debug($user); ?>
		<?php debug($_POST); ?>
		-->

		{{ Form::model($user) }}

		<div class="username">
		{{ Form::label('username', 'username') }}
		{{ Form::text('username') }}
		</div>

		<div class="email">
		{{ Form::label('email', 'email address') }}
		{{ Form::email('email', $value = null, $attributes = array()) }}
		</div>

		<div class="email">
		{{ Form::label('password', 'Password') }}
		{{ Form::password('password')}}
		</div>

		@foreach (Array('first_name', 'last_name','phone','address','city','state','zip') as $key)

		<div class="{{ $key }}">
		{{ Form::label($key) }}
		{{ Form::text($key) }}
		</div>

		@endforeach

		{{ Form::submit() }}
		{{ Form::close() }}

@stop