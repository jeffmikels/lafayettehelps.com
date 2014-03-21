@extends('layout')

@section('content')

		<h2>Send Password Reminder</h2>
		{{ Form::open(array('class' => 'form', 'role'=>'form')) }}
		
		<p>Enter your email address or your username.</p>

		<div class="username form-group">
			{{ Form::label('username', 'Username') }}
			{{ Form::text('username', '', array('placeholder' => 'username', 'class' => 'form-control')) }}
		</div>

		<div class="email form-group">
			{{ Form::label('email', 'Email') }}
			{{ Form::email('email', '', array('placeholder' => 'email', 'class' => 'form-control')) }}
		</div>

		<div class="form_buttons">
			{{ Form::submit('Send Reminder', array('class'=>'btn btn-default')) }}
		</div>
		{{ Form::close() }}

@stop
