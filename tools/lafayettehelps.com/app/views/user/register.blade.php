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


		<div class="username form-group">
		{{ Form::label('username', 'Username') }}
		{{ Form::text('username', null, $attributes = array('class' => 'form-control', 'placeholder' => 'Enter Username'))}}
		</div>

		<div class="email form-group">
		{{ Form::label('email', 'Email Address') }}
		{{ Form::email('email', $value = null, $attributes = array('class' => 'form-control', 'placeholder' => 'Enter Email Address')) }}
		</div>

		<div class="password form-group">
		{{ Form::label('password', 'Password') }}
		{{ Form::password('password', $attributes = array('class' => 'form-control'))}}
		</div>

		@foreach (Array('first_name', 'last_name','phone','address','city','state','zip') as $key)

		<div class="{{ $key }} form-group">
		{{ Form::label($key) }}
		{{ Form::text($key,'', $attributes = array('class' => 'form-control') ) }}
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