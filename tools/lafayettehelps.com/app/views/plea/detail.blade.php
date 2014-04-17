@extends('layout')

@section('content')
	<h1><a href="{{ action('PleaController@showDetail', $plea->id) }}">{{ $plea->summary }}</a>
	@if ( me()->hasPermissionTo('edit', $plea) )
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
		{{ $plea->details }}
	</div>

	@if ($plea->deadline)
	<div class="deadline alert alert-warning">
		This request for help has been given a deadline of {{$plea->deadline}}. On the day after that date, it will be marked inactive on this website. Please offer any assistance before then.
	</div>
	@endif

	<div class="pledges well">
		<h1>Pledges</h1>
		<div class="pledges">
			<?php $total = 0; ?>
			<?php $monetaryPledges = $plea->monetaryPledges(); ?>
			<div class="pledge well-sm">
				@if (count($monetaryPledges))
				<h2>Monetary Pledges</h2>
				<ul>
					@foreach ($plea->monetaryPledges() as $pledge)
					<?php $total += $pledge->dollars; ?>
					<li class="pledge_{{$pledge->id}}"><strong>${{ $pledge->dollars }}</strong> {{ $pledge->author->permalink() }}</li>
					@endforeach
				</ul>
				<strong>Total Pledged:</strong> ${{number_format($total,2)}}
				@else
				<p>No monetary pledges have yet been made.</p>
				@endif
			</div>

			<?php $alternativePledges = $plea->alternativePledges(); ?>
			<div class="pledge well-sm">
				@if (count($alternativePledges))
				<h2>Alternative Pledges</h2>
				<ul>
					@foreach ($plea->alternativePledges() as $pledge)
					<li class="pledge_{{$pledge->id}}"><p>{{ $pledge->alternatives }}</p>{{ $pledge->author->permalink() }}</li>
					@endforeach
				</ul>
				@else
				<p>No alternative pledges have been made for this request.</p>
				@endif
			</div>
		</div>
		<?php
			$needed = $plea->dollars - $total;
			if ($needed < 0)
			{
				$need_message = "<strong>Unbelievable!</strong> The financial goal for this request has been exceeded by $" . number_format((-1) * $needed, 2);
				$need_class = 'success';
			}
			elseif ($needed == 0)
			{
				$need_message = "<strong>Hooray!</strong> The financial goal for this request has been reached.";
				$need_class = 'success';
			}
			elseif ($needed < 50)
			{
				$need_message = "<strong>Almost there!</strong> The pledges are only $". number_format($needed,2). " short. We can reach this goal!!";
				$need_class = 'warning';
			}
			else
			{
				$need_message = "The pledges are still $" . number_format($needed, 2) . " away from meeting the goal.";
				$need_class = "danger";
			}
		?>
		<div class="alert alert-{{$need_class}}">{{$need_message}}</div>
	</div>

	@if (!Auth::check())
	<div class="alert alert-primary">
	To make a pledge or to leave a comment, you need to be logged in. <a href="{{route('login')}}">Click Here.</a>
	</div>
	@endif

	@if ($needed > 0 && Auth::check())
	<div class="well">
		<h2>Make a Pledge</h2>
		<div class="new_pledge">
			{{$errors->first('plea_id') }}
			{{ Form::open( array('action' => 'PledgeController@add', 'class'=>'form', 'role'=>'form' ) ) }}
			{{ Form::hidden('plea_id', $plea->id) }}
			<div class="form-group">
				{{ $errors->first('dollars') }}
				{{ Form::label('dollars', 'Dollars') }}
				<div class="input-group">
					<span class="input-group-addon">$</span>
					{{ Form::text('dollars', NULL, array('class'=>'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				{{ $errors->first('alternatives') }}
				{{ Form::label('alternatives', 'Alternatives') }}
				{{ Form::text('alternatives', NULL, array('class' => 'form-control')) }}
			</div>
			<div class="form-group">
				{{ Form::submit( NULL, array('class'=>'btn btn-primary')) }}
			</div>
			{{ Form::close() }}
		</div>
	</div>
	@endif

	@if (Auth::check())
	<div class="comments well">
		<h1>Comments</h1>
		<div class="comments">
		@if (count($plea->comments))
		@foreach ($plea->comments as $comment)
		<div class="comment comment_{{$comment->id}} well-sm">
			<div class="comment_content">
			{{ $comment->comment }}
			</div>
			<div class="comment_author">
			posted by: {{ $comment->author->permalink() }} on {{ $comment->created_at->toDayDateTimeString() }}
			</div>
		</div>
		@endforeach
		@endif
		<strong>There are no comments yet.</strong>
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
	@endif


	@if (isAdmin())
	<div class="alert alert-warning">
		<h2>Administrative Details</h2>
		<small>You have been granted administrative access to this site. Please use your power with caution.</small>
		<div class="admin_details">
			<a class="btn btn-danger" href="#" onclick="$('#plea-reject-form').show();return false;">Reject this Request</a>
			<div class="modal" id="plea-reject-form">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4>Reject Request</h4>
						</div>
						<div class="modal-body">
							{{ Form::open( array('route' => array('pleareject', $plea->id), 'class'=>'form','role' => 'form') ) }}
							<div class="form-group">
							{{ Form::label('reason','Enter your reason for rejecting this request') }}
							{{ Form::text('reason', NULL, array('class'=>'form-control')) }}
							</div>
						</div>
						<div class="modal-footer">
			        <button onclick="$('.modal').hide();" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							{{ Form::submit('Reject this Request', array('class' => 'btn btn-danger')) }}
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

@stop
