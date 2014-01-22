@extends('layout')

@section('content')

<h2>{{ $class }} Objects</h2>
<a href="/{{ strtolower($class) }}/add">Add New {{ $class }}</a>

@foreach ($objects as $object)

<h3><a href="/{{ strtolower($class) }}/{{ $object->id }}">{{ $class }} #{{ $object->id }}</a></h3>
<ul>
@foreach ($object->getProperties() as $prop)
<li>{{ $prop }} :: {{ $object->$prop }}</li>
@endforeach
</ul>

@endforeach


@stop
