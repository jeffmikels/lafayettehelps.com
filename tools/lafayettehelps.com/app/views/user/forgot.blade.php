@extends('layout')

@section('content')

		{{ Form::open() }}

		<h2>Send Password Reminder</h2>
		<p>Enter your email address</p>

		<div class="email">
		{{ Form::label('email', 'Email') }}
		{{ Form::email('email')}}
		</div>

		<div class="form_buttons">
		{{ Form::submit() }}
		</div>
		{{ Form::close() }}


@stop
