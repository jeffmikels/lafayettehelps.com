@extends('layout')

@section('content')

	<h1>All Requests</h1>

	<div class="list-group plea-list">

	@foreach ($pleas as $plea)

		<div class="list-group-item">
			<a href="{{ action('PleaController@showDetail', $plea->id) }}"><h2>{{ $plea->summary }}</h2></a>
			<div class="meta">
				<span class="meta-item date">{{$plea->updated_at->toDayDateTimeString()}}</span>
				<span class="meta-item author">{{$plea->author->getName()}}<?php show_mini_reputation($plea->author); ?></span>
				<span class="meta-item money">
					Money Requested:
					@if (floatval($plea->dollars) > 0)
						${{ floatval($plea->dollars) }}
					@else
						None
					@endif
				</span>
				@if ($plea->deadline)
					<span class="meta-item deadline">
					Deadline: {{$plea->deadline}}
					</span>
				@endif
			</div>
		</div>

	@endforeach

	{{ $pleas->links() }}


@stop
