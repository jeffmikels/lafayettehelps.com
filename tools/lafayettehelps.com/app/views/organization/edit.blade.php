@extends('layout')

@section('content')


	@if ($organization->id)
		<h1>Edit {{ $organization->name }}</h1>
	@else
		<h1>New Organization</h1>
		<p>If the addition of this organization is approved by the site admins, you will be marked as this organization's first administrative user.</p>

	@endif

	{{ Form::model($organization,array('role'=>'form', 'autocomplete' => 'off')) }}
	
	@if ($organization->id)
	{{ Form::hidden('id',$organization->id) }}
	@endif

	<?php $show_fields = Array('name','email','phone','address','city','state','zip'); ?>

	@foreach ($show_fields as $key)

	<div class="{{ $key }} form-group">
	{{ Form::label($key) }}
	{{ Form::text($key, $value = null, array('class' => 'form-control')) }}
	</div>

	@endforeach

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
					{{ Form::select('status', $organization->getStatusOptions(), null, array('class' => 'form-control')) }}
					</div>
				</div>
				<div class="org_admins">
				</div>
			</div>
		</div>
		@endif


	<div class="form_buttons">
	{{ Form::submit(null, array('class' => 'btn btn-block btn-primary')) }}	
	</div>
	{{ Form::close() }}


@stop
