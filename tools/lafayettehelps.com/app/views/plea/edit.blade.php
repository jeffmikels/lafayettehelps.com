@extends('layout')

@section('content')


	@if (isset($plea->id))
		<h1>Edit Request #{{ $plea->id }}</h1>
	@else
		<h1>New Request</h1>
	@endif

	<div class="well">
	<p>Though we can't guarantee that all requests will be met, your request will have a much better chance of being met if you create a good description. Please describe your need in as much detail as you can.</p>
	<p>Please don't put personal contact information on this form. We want people to contact you using the contact methods already available through this site.</p>
	</div>

	<h2>Request Details</h2>

		{{ Form::model($plea, array('role' => 'form', 'class' => 'form-horizontal')) }}

		@if (isset($plea->id))
		{{ Form::hidden('id',$plea->id) }}
		@endif


		<div class="summary form-group">
			<div class="col-sm-3 control-label">
				{{ Form::label('summary','Brief Description', array('class' => '')) }}
				<div class="explanation">Describe your need in a sentence.</div>
			</div>
			<div class="col-sm-9">
				{{ Form::text('summary', NULL, array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="deadline form-group">
			<div class="col-sm-3 control-label">
				{{ Form::label('deadline','Deadline', array('class' => '')) }}
				<div class="explanation">Give this request a deadline.</div>
			</div>
			<div class="col-sm-9">
				<div class="input-append">
					{{ Form::text('deadline', $value=null, $attributes=array('data-date-format'=> 'mm/dd/yyyy', 'class' => 'date-widget form-control')) }}
				</div>
			</div>
		</div>

		<div class="details form-group">
			<div class="col-sm-3 control-label">
				{{ Form::label('details','Detailed Description', array('class' => '')) }}
				<div class="explanation">This is where you write down all the details of your need.</div>
			</div>
			<div class="col-sm-9">
				<div class="well">
				{{ Form::textarea('details',NULL,array('class' => 'form-control wysiwyg')) }}
				</div>
			</div>
		</div>

		<div class="dollars form-group">
			{{ Form::label('dollars','Dollars Requested', array('class' => 'col-sm-3 control-label')) }}
			<div class="col-sm-9">
				<div class="input-group">
					<span class="input-group-addon">$</span>
					{{ Form::text('dollars',NULL,array('class' => 'form-control')) }}
				</div>
			</div>
		</div>

		<div class="alternatives form-group">
			<div class="col-sm-3 control-label">
				{{ Form::label('alternatives','Alternatives to Money', array('class' => '')) }}
				<div class="explanation">Would anything other than money help you out?</div>
			</div>
			<div class="col-sm-9">
				{{ Form::text('alternatives',NULL,array('class' => 'form-control')) }}
			</div>
		</div>

		<div class="form_buttons form-group">
			<div class="">
				{{ Form::submit('Submit',array('class' => 'btn btn-info btn-lg')) }}
			</div>
		</div>
		{{ Form::close() }}


@stop
