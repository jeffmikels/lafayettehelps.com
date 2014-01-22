@extends('layout')

@section('content')



		<p>@if (isset($user->id)) Edit User #{{ $user->id }}@endif</p>

		{{-- debug($user) --}}
		{{-- debug($_POST) --}}


		{{ Form::model($user) }}

		<h2>Account Details for {{$user->first_name}} {{$user->last_name}}</h2>

		<div class="username">
		{{ Form::label('username', 'Username') }}
		{{ Form::text('username') }}
		</div>

		<div class="email">
		{{ Form::label('email', 'Email address') }}
		{{ Form::email('email', $value = null, $attributes = array()) }}
		</div>

		<div class="password">
		{{ Form::label('password', 'Password') }}
		{{ Form::password('password')}}
		</div>

		<div class="password_confirm">
		{{ Form::label('password_confirm', 'Password Again') }}
		{{ Form::password('password_confirm')}}
		</div>


{{-- TODO show more information for administrators --}}
{{-- if Auth::user()->role is administrator --}}
{{-- show status field --}}
{{-- show role field --}}
{{-- show reputation field --}}

		<h2>Contact Details</h2>

		<?php $show_fields = Array('first_name','phone'); ?>
		<?php if (Auth::user()->role == 'administrator' || Auth::user() == $user)
			$show_fields = Array('first_name','last_name','phone','address','city','state','zip'); ?>

		@foreach ($show_fields as $key)

		<div class="{{ $key }}">
		{{ Form::label($key) }}
		{{ Form::text($key) }}
		</div>

		@endforeach

		@if (Auth::user()->role == 'administrator')
		<h2>Administrative Details</h2>
		<small>You have been granted administrative access to this site. Please use your power with caution.</small>
		<div class="admin_details">
			<div class="status">
			{{ Form::label('status') }}
			{{ Form::select('status', $user->getStatusOptions()) }}
			</div>
			<div class="role">
				{{Form::label('role')}}
				{{Form::select('role', $user->getRoleOptions())}}
			</div>
		</div>
		@endif

		<div class="form_buttons">
		{{ Form::submit() }}
		</div>
		{{ Form::close() }}


@stop
