<!DOCTYPE html>
<html>
	<head>
		<title>lafayettehelps.com</title>

		<link type="text/css" rel="stylesheet" href="/css/style.css" media="all" />
		<style type="text/css">
		</style>
	</head>
	<body>
		<div id="container">

			<div id="header">
				<h1><a href='//test.lafayettehelps.com'>test.lafayettehelps.com</a></h1>
			</div>

			<div id="menu">
				<ul class="navbar">
					<li><a href="/Requests">Requests</a>

					@if (Auth::check())

					@if (Auth::user()->role == 'administrator')
					<li><a href="/users">Users</a>
					<li><a href="/orgs">Orgs</a>
					@endif

					<li><a href="/user/{{ Auth::user()->id }}">My Account</a>
					<li><a href="/user/logout">Logout</a>
					@else
					<li><a href="/user/add">Register</a>
					<li><a href="/user/login">Login</a>
					@endif

				</ul>
			</div>

			<div id="content">
			@yield('content')
			</div>

		<div>
	</body>
</html>
<?php
Session::forget('status');
Session::forget('message');
?>
