@extends('layout')

@section('content')

<div class="jumbotron">
	<h1>How can we help?</h1>

	<p>
		<strong>lafayettehelps.com</strong> is a newly developing community of people who are committed to one simple idea:
		<strong>People Help People</strong>.
	</p>
	<p>
		Every one of us has been on both sides of help. <strong>Sometimes you are the helper,
		and sometimes you need a little help.</strong> That's the way life works, and we're okay with that.
		So today, if you are someone who needs help, please let us know. If today, you are someone
		who can help someone else, please jump in and do so.
	<p>
		<strong>Let's be part of the solution for our community, together.</strong>
	</p>

</div>

<div class="row">
	<div class="col-lg-12">
		<a class="btn btn-info btn-lg btn-block" href="{{route('pleaadd')}}">
			Ask for Help
		</a>
	</div>
</div>

@if($urgent)
<div class="row">
	<div class="col-lg-12">
		<h2>Urgent Requests</h2>
	</div>
</div>
<div class="row">
	<?php foreach($newest as $plea): ?>
		<div class="col-xs-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>DEADLINE:</strong> {{$plea->deadline}}
				</div>
				<div class="panel-body">
					<div>
						<strong>Need: </strong>{{ $plea->dollars }}<br />
						<strong>Alternatives: </strong> {{$plea->alternatives}}<br />
						<strong>Details: </strong> <a href="{{route('pleadetail', array('id' => $plea->id))}}">Click here</a>
					</div>
					<div class="well">
						{{ $plea->summary }}
					</div>
					{{ show_reputation($plea->author); }}
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
@endif

@if($newest)
<div class="row">
	<div class="col-lg-12">
		<h2>Newest Requests</h2>
	</div>
</div>
<div class="row">
	<?php foreach($newest as $plea): ?>
		<div class="col-xs-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					<strong>POSTED ON:</strong> {{$plea->created_at->toDayDateTimeString()}}
				</div>
				<div class="panel-body">
					<div>
						<strong>Need: </strong>{{ $plea->dollars }}<br />
						<strong>Alternatives: </strong> {{$plea->alternatives}}<br />
						<strong>Details: </strong> <a href="{{route('pleadetail', array('id' => $plea->id))}}">Click here</a>
					</div>
					<div class="well">
						{{ $plea->summary }}
					</div>
					{{ show_reputation($plea->author); }}
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
@endif

@stop