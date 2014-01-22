@extends('layout')

@section('content')

		<h2>Change Your Password</h2>

		{{ Form::open() }}
		{{ Form::hidden('token', $token) }}
		
		
		<div class="email">
			{{ Form::label('email', 'Email address') }}
			{{ Form::email('email', $value = null, $attributes = array('autocomplete'=>'off')) }}
		</div>

		<div class="password">
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password')}}
		</div>

		<div class="password_confirm">
			{{ Form::label('password_confirmation', 'Password Again') }}
			{{ Form::password('password_confirmation')}}
		</div>


		<div class="form_buttons">
			{{ Form::submit() }}
		</div>
		
		{{ Form::close() }}


@stop
