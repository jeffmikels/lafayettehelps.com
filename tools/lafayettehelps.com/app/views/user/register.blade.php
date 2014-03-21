@extends('layout')

@section('content')

		<!--
		<?php debug($user); ?>
		<?php debug($_POST); ?>
		-->

		<div class="jumbotron">
			<p>Thank you for registering a new account with us. With this account, you will be able to post requests for help, post recommendations for other people on the site, submit organizations for approval, administer organizations, if you have the authority to do so, and most importantly, build relationships of helping others.
			<p>For more information on this site and how it works, be sure to visit our "<a href="/info">Information Page</a>."
		</div>

		{{ Form::model($user, array('role' => 'form')) }}


		<div class="username form-group <?php if ($errors->has('username')) print "has-error"; ?>">

		{{ Form::label('username', 'Username') }}
		{{ Form::text('username', null, $attributes = array('class' => 'form-control', 'placeholder' => 'Enter Username'))}}
		<small>{{ $errors->first('username') }} </small>

		</div>

		<div class="email form-group <?php if ($errors->has('email')) print "has-error"; ?>">
		{{ Form::label('email', 'Email Address') }}
		{{ Form::email('email', $value = null, $attributes = array('class' => 'form-control', 'placeholder' => 'Enter Email Address')) }}
		<small>{{ $errors->first('email') }} </small>
		</div>

		<div class="password form-group <?php if ($errors->has('password')) print "has-error"; ?>">
		{{ Form::label('password', 'Password') }}
		{{ Form::password('password', $attributes = array('class' => 'form-control'))}}
		<small>{{ $errors->first('password') }} </small>
		</div>

		<div class="password_confirmation form-group <?php if ($errors->has('password_confirmation')) print "has-error"; ?>">
		{{ Form::label('password_confirmation', 'Confirm Password') }}
		{{ Form::password('password_confirmation', $attributes = array('class' => 'form-control'))}}
		</div>

		@foreach (Array('first_name', 'last_name','phone','address','city','state','zip') as $key)

		<div class="{{ $key }} form-group <?php if ($errors->has($key)) print "has-error"; ?>">
		{{ Form::label($key) }}
		{{ Form::text($key,'', $attributes = array('class' => 'form-control') ) }}
		<small>{{ $errors->first($key) }} </small>
		</div>

		@endforeach

		@if ($user->id)
		{{ Form::hidden('id', $value = $user->id) }}
		@endif

		<div class="submit-button form-group">
		{{ Form::submit(null,$attributes = array('class' => 'btn btn-block btn-primary')) }}
		</div>

		{{ Form::close() }}

@stop