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
		<a class="btn btn-info btn-lg btn-block" href="{{route('addplea')}}">
			Ask for Help
		</a>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<h2>Newest Items</h2>
	</div>
</div>
<div class="row">
	<?php for($i = 1; $i < 10; $i++): ?>
		<div class="col-xs-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					Help Wanted
				</div>
				<div class="panel-body">
					I need some help paying my electrical bills.
				</div>
			</div>
		</div>
	<?php endfor; ?>
</div>


@stop