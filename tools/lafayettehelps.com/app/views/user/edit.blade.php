@extends('layout')

@section('content')

		{{ Form::model($user,array('role'=>'form', 'autocomplete' => 'off')) }}

		@if ($user->id)
		<h2>Account Details for {{ $user->permalink() }}</h2>
		@else
		<h2>Add New User</h2>
		@endif

		<div class="well">
			<div class="username form-group">
			{{ Form::label('username', 'Username') }}
			{{ Form::text('username', $value = null, array('class' => 'form-control')) }}
			</div>

			<div class="email form-group">
			{{ Form::label('email', 'Email address') }}
			{{ Form::email('email', $value = null, array('class' => 'form-control')) }}
			</div>

			<div class="password form-group">
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password', array('class' => 'form-control'))}}
			</div>

			<div class="password_confirm form-group">
			{{ Form::label('password_confirm', 'Password Again') }}
			{{ Form::password('password_confirm', array('class' => 'form-control'))}}
			</div>
		</div>
		<h2>Contact Details</h2>
		
		<div class="well">
			
			<?php $show_fields = Array('first_name','phone'); ?>
			<?php if (Auth::user()->role == 'administrator' || Auth::user() == $user)
				$show_fields = Array('first_name','last_name','phone','address','city','state','zip'); ?>

			@foreach ($show_fields as $key)

			<div class="{{ $key }} form-group">
			{{ Form::label($key) }}
			{{ Form::text($key, $value = null, array('class' => 'form-control')) }}
			</div>
			@endforeach
		</div>
		@if (Auth::user()->role == 'administrator')
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h2>Administrative Details</h2>
			</div>
			<div class="panel-body">
				<small>You have been granted administrative access to this site. Please use your power with caution.</small>
				<div class="admin_details">
					<div class="status form-group">
					{{ Form::label('status') }}
					{{ Form::select('status', $user->getStatusOptions(), null, array('class' => 'form-control')) }}
					</div>
					<div class="role form-group">
						{{Form::label('role')}}
						{{Form::select('role', $user->getRoleOptions(), null, array('class' => 'form-control')) }}
					</div>
				</div>
			</div>
		</div>
		@endif
		<div class="form_buttons form-group">
		{{ Form::submit(null, array('class' => 'btn btn-block btn-primary')) }}
		</div>
		{{ Form::close() }}


@stop
