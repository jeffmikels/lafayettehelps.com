@extends('layout')

@section('content')
	<h1><a href="{{ action('PleaController@showDetail', $plea->id) }}">{{ $plea->summary }}</a>
	@if ( hasPermissionTo('edit', $plea) )
	<small><a href="{{ action('PleaController@edit', $plea->id) }}">[EDIT]</a></small>
	@endif
	</h1>

	<div class="meta">
		Last Updated: <span class="date">{{$plea->updated_at->toDayDateTimeString()}}</span><br />
		Posted by: <span class="author">{{$plea->author->permalink()}}</span>
		<?php show_mini_reputation($plea->author); ?>
	</div>

	<h2>
		Money Requested:
		@if (floatval($plea->dollars) > 0)
			${{ floatval($plea->dollars) }}
		@else
			None
		@endif
	</h2>

	<h2>
		Alternatives to Money:
		@if ($plea->alternatives)
			{{{ $plea->alternatives }}}
		@else
			None
		@endif
	</h2>

	<div class="description well">
		<p>{{{ str_replace("\n\n", "</p><p>", $plea->details) }}}</p>
	</div>

	@if ($plea->deadline)
	<div class="deadline alert alert-warning">
		This request for help has been given a deadline of {{$plea->deadline}}. On the day after that date, it will be marked inactive on this website. Please offer any assistance before then.
	</div>
	@endif



	<div class="comments well">
		<h1>Comments</h1>
		<div class="comments">
		@foreach ($plea->comments as $comment)
		<div class="comment comment_{{$comment->id}} well-sm">
			<div class="comment_content">
			{{ $comment->comment }}
			</div>
			<div class="comment_author">
			posted by: {{ $comment->author->permalink() }}
			</div>
		</div>
		@endforeach
		</div>


		<h2>Leave a Comment</h2>
		<div class="new_comment">
		{{ Form::open( array('action' => 'CommentController@add', 'class'=>'form', 'role'=>'form' ) ) }}
		{{ Form::hidden('commentable_type', 'plea') }}
		{{ Form::hidden('commentable_id', $plea->id ) }}
		{{ Form::textarea('comment', NULL, array('class'=>'form-control')) }}<br />
		{{ Form::submit( NULL, array('class'=>'btn btn-primary')) }}
		{{ Form::close() }}
		</div>


	</div>

	@if (isAdmin())
	<div class="alert alert-warning">
	<h2>Administrative Details</h2>
	<small>You have been granted administrative access to this site. Please use your power with caution.</small>
	<div class="admin_details">
	</div>
	</div>
	@endif

@stop
