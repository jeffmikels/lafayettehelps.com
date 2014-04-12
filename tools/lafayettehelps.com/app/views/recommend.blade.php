@extends('layout')

@section('content')
	
	<div class="well">
		<h2>Submit Recommendation for {{ $user->getName() }}</h2>

		<div class="contact-form">
			{{Form::open(array('role'=>'form'))}}
			{{Form::hidden('contributed_for', $user->id)}}
			<div class="form-group">
				{{ $errors->first('content')}}
				{{Form::label('body', 'Your Recommendation')}}
				{{Form::textarea('body', NULL, array('class'=>'form-control wysiwyg'))}}			
			</div>
			<div class="form-group">
				{{Form::submit('Submit Recommendation', array('class'=>'btn btn-primary form-control'))}}
			</div>
			{{Form::close()}}
		</div>
	</div>
	
@stop