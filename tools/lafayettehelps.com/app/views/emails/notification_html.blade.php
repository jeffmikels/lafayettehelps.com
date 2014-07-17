<?php $object = $notification['object']; ?>
<h2>lafayettehelps.com</h2>

<h3>Your {{$noun}} {{$verb}}.</h3>
----------------------------------------------------

@if ($notification['type'] == 'plea')

<h4>Request Title:</h4>

{{ $object->summary }}

<p>View more details about your Request here:</p>

<a href="{{ $object->permalink() }}">Request Details</a>

@elseif ($notification['type'] == 'pledge')

<p>You have made a pledge to help with the following need:</p>

{{$object->plea->summary}}

<p>View more details about your Request here:</p>

<a href="{{$object->plea->permalink()}}">Request Details</a>

@endif

<hr />
<p style="font-size:.8em;">This email was sent to you by <a href="{{route('home')}}">lafayettehelps.com</a>. To update your email notification settings, visit this page: <a href="{{route('dashboard')}}">My Dashboard.</a></p>