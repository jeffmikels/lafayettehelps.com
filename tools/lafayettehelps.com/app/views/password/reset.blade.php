@extends('layout')

@section('content')

		<h2>Change Your Password</h2>

		{{ Form::open(array('class' => 'form', 'role'=>'form')) }}
		{{ Form::hidden('token', $token) }}
		
		
		<div class="email form-group">
			{{ Form::label('email', 'Email address') }}
			{{ Form::email('email', $value = null, $attributes = array('autocomplete'=>'off', 'class'=>'form-control')) }}
		</div>

		<div class="password form-group">
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password', $attributes = array('class'=>'form-control')) }}
		</div>

		<div class="password_confirmation form-group">
			{{ Form::label('password_confirmation', 'Password Again') }}
			{{ Form::password('password_confirmation', $attributes = array('class'=>'form-control')) }}
		</div>

		
		<div class="form_buttons form-group">
			{{ Form::submit('Submit', array('class'=>'btn btn-default')) }}
		</div>
		
		{{ Form::close() }}


@stop
