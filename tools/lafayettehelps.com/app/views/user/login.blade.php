@extends('layout')

@section('content')

		<h2>Login</h2>
		{{ Form::open(array('class' => 'form', 'role'=>'form')) }}

		<div class="form-group">
			{{ Form::label('username', 'Username') }}
			{{ Form::text('username', '', array('placeholder' => 'username', 'class'=>'form-control')) }}
		</div>

		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" />
		</div>

		<div class="form_buttons">
		<a class="btn btn-info" href="/password/forgot">Forgot Password</a>
		{{ Form::submit('Submit', array('class'=>'btn btn-default')) }}
		</div>
		{{ Form::close() }}


@stop
