@extends('layout')

@section('content')

		@if (Session::has('status'))
			{{ trans(Session::get('message')) }}

			<?php Session::forget('status'); ?>
			<?php Session::forget('message'); ?>

		@endif


		<p>Edit User #{{ $user->id }}</p>

		{{-- debug($user) --}}
		{{-- debug($_POST) --}}
		
		Logged in as: {{ Auth::user()->username }}


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
			{{ Form::text('status') }}
			</div>
			<div class="role">
				{{Form::label('role')}}
				{{Form::text('role')}}
			</div>
		</div>
		@endif
		
		<?php $reputation_class="green"; ?>
		<?php if ($user->reputation < 75) $reputation_class='yellow'; ?>
		<?php if ($user->reputation < 50 ) $reputation_class='orange'; ?>
		<?php if ($user->reputation < 25) $reputation_class='red'; ?>

		<h2>Reputation</h2>
		<div class="reputaton_bar" style="width:100%;box-sizing:border-box;border:1px solid #777;border-radius:3px;overflow:hidden;">
			<div class="reputation_color {{$reputation_class}}" style="width:{{ ($user->reputation) }}%;background-color:{{$reputation_class}};">&nbsp;</div>
		</div>

		{{ Form::submit() }}
		{{ Form::close() }}


@stop