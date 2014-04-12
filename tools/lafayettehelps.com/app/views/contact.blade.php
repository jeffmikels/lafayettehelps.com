@extends('layout')

@section('content')
	
	<div class="well">
		<h2>Send Email to {{ $object->name }}</h2>

		<div class="contact-form">
			{{Form::open(array('role'=>'form'))}}
			<div class="form-group">
				{{Form::label('subject', 'Email Subject')}}
				{{Form::text('subject', NULL, array('class'=>'form-control'))}}
			</div>
			<div class="form-group">
				{{Form::label('content', 'Email Content')}}
				{{Form::textarea('content', NULL, array('class'=>'form-control wysiwyg'))}}			
			</div>
			<div class="form-group">
				{{Form::submit('Send Email', array('class'=>'btn btn-primary form-control'))}}
			</div>
			{{Form::close()}}
		</div>
	</div>
	
@stop