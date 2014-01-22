@extends('layout')

@section('content')


	@if ($organization->id)
		<h1>Edit {{ $organization->name }}</h1>
	@else
		<h1>New Organization</h1>
		<p>If the addition of this organization is approved by the site admins, you will be marked as this organization's first administrative user.</p>

	@endif


		{{-- debug($organization) --}}
		{{-- debug($_POST) --}}

		{{ Form::model($organization) }}
		
		@if ($organization->id)
		{{ Form::hidden('id',$organization->id) }}
		@endif

		<h2>Organization Details</h2>

		<?php $show_fields = Array('name','email','phone','address','city','state','zip'); ?>

		@foreach ($show_fields as $key)

		<div class="{{ $key }}">
		{{ Form::label($key) }}
		{{ Form::text($key) }}
		</div>

		@endforeach

		<div class="form_buttons">
		{{ Form::submit() }}
		</div>
		{{ Form::close() }}


@stop
