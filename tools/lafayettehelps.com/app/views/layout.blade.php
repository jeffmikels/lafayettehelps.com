<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>lafayettehelps.com</title>

	<!-- ALL CSS FILES -->
	<!-- BOOTSTRAP RESOURCES -->
	<!-- Latest compiled and minified CSS -->
	<!--<link rel="stylesheet" href="/bootstrap-3.0.3/css/bootstrap.min.css">-->
	<link rel="stylesheet" href="/css/bootstrap_simplex.css">

	<!-- BOOTSTRAP DATEPICKER -->
	<link href="/css/datepicker.css" rel="stylesheet">

	<!-- WYSIWYG CSS -->
	<link rel="stylesheet" type="text/css" href="/bootstrap-wysihtml5/bootstrap-wysihtml5.css"></link>


	<!-- TABLESORTER -->
	<link type="text/css" rel="stylesheet" href="/tablesorter/css/theme.default.css" media="all" />


	<!-- MY PERSONAL CSS -->
	<link type="text/css" rel="stylesheet" href="/css/style.css" media="all" />


	<!-- CUSTOM CSS -->
	<style type="text/css">
	@import url(http://fonts.googleapis.com/css?family=McLaren);
	@import url(http://fonts.googleapis.com/css?family=Lora:400italic,700italic,400,700);

	body
	{
		padding-top:81px;
	}

	.well {font-size:1em;}

	/* BODY BACKGROUND */
	body
	{
		/*
		background-color: #b6d8ed;
		background-image:url(images/wave.jpg);
		background-repeat:no-repeat;
		background-position:fixed;
		background-size:100%,100%;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
		background-size:cover;
		*/
	}
	#content {font-weight:300;}

	/*h1, h2, h3, h4, h5, h6 {font-weight:700; font-family: McLaren; letter-spacing: -2px;}*/
	.jumbotron h1 {font-weight:700;}

	/*a {text-decoration:none; display: inline-block;}*/
	.form-horizontal .control-label {padding-top:0;}
	.form-horizontal .control-label label {margin:0;}
	.explanation {font-size:.7em;}
	.reputation_bar_mini {display:inline-block;}

	.plea-list .meta .meta-item {display: inline-block;font-size:.7em;background:#eee;padding:2px 6px; font-weight:bold;margin:1px 5px;border-radius:3px;}

	#old-content {padding: 20px; background:rgba(255,255,255,.9);}

	.note-meta {text-transform:uppercase;font-weight:700;font-size:.8em;}
	.note-body {font-weight:100; font-size:1.5em;margin:3px;}

	</style>



	<!-- SCRIPT FILES -->
	<!-- jquery -->
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/pepper-grinder/jquery-ui.css" />

	<!-- Latest compiled and minified Bootstrap JavaScript -->
	<!-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> -->
	<script src="/js/bootstrap.min.js"></script>

	<!-- BOOTSTRAP DATEPICKER -->
	<script src="/js/bootstrap-datepicker.js"></script>

	<!-- WYSIHTML5 SCRIPT -->
	<script type="text/javascript" src="/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
	<script type="text/javascript" src="/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>

	<!-- TABLESORTER -->
	<script type="text/javascript" src="/tablesorter/js/jquery.tablesorter.js"></script>


</head>
<body>
	<!-- MAIN NAVIGATION MENU GOES HERE -->
	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<a href="/" class="navbar-brand">lafayettehelps.com</a>
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="navbar-collapse collapse" id="navbar-main">
				<ul class="nav navbar-nav">
					<li><a tabindex="-1" href="{{route('info')}}">Info</a></li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Requests<span class="caret"></span></a>
						<ul class="dropdown-menu" aria-labelledby="requests">
							<li class="dropdown-header">Requests for Help</li>
							<li><a tabindex="-1" href="{{route('pleas')}}">All Requests</a></li>
							<!--
							<li><a tabindex="-1" href="../cerulean/">Requests I'm Tracking</a></li>
							<li><a tabindex="-1" href="../cosmo/">Requests I Posted</a></li>
							<li class="divider"></li>
							-->
							<li><a tabindex="-1" href="{{route('pleaadd')}}">Submit New Request</a></li>
						</ul>
					</li>
					<!-- WE ARE DISABLING THE "OFFERS" FUNCTIONALITY -->
					<!--
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Offers<span class="caret"></span></a>
						<ul class="dropdown-menu" aria-labelledby="offers">
							<li class="dropdown-header">Offers to Help</li>
							<li><a tabindex="-1" href="../amelia/">All Offers</a></li>
							<li><a tabindex="-1" href="../cerulean/">Offers I'm Tracking</a></li>
							<li><a tabindex="-1" href="../cosmo/">Offers I Posted</a></li>
							<li class="divider"></li>
							<li><a tabindex="-1" href="../cerulean/">Submit New Offer</a></li>
						</ul>
					</li>
					-->
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">People &amp; Organizations<span class="caret"></span></a>
						<ul class="dropdown-menu" aria-labelledby="users">
							<li class="dropdown-header">Users</li>
							<li><a tabindex="-1" href="{{route('users')}}">Find a User</a></li>
							@if (isAdmin() or isOrgAdmin())
							<li><a tabindex="-1" href="{{route('adduser')}}">Submit a new User</a></li>
							@endif
							<li class="dropdown-header">Organizations</li>
							<li><a tabindex="-1" href="{{route('organizations')}}">Find an Organization</a></li>
							@if (Auth::check())
							<li><a tabindex="-1" href="{{route('addorganization')}}">Submit an Organization</a></li>
							@endif
							@if (isAdmin() or isOrgAdmin())
							<li class="dropdown-header">Relationships</li>
							<li><a tabindex="-1" href="{{route('relationships')}}">View all relationships</a></li>
							@endif
						</ul>
					</li>
					@if (! Auth::check())
					<li><a tabindex="-1" href="{{route('register')}}">Register</a></li>
					<li><a tabindex="-1" href="{{route('login')}}">Login</a></li>
					@else
					<li class="dropdown">

						<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">My Account<span class="caret"></span></a>
						<ul class="dropdown-menu" aria-labelledby="account">
							<li class="dropdown-header">My Account</li>
							<li><a tabindex="-1" href="{{route('dashboard')}}">My Dashboard</a></li>
							<li><a tabindex="-1" href="{{route('userprofile', array('id' => me()->id))}}">My Public Profile</a></li>
							<li><a tabindex="-1" href="{{route('pleasbyuser', array('user_id' => me()->id))}}">My Requests</a></li>
							<li><a tabindex="-1" href="{{route('pledgesbyuser', array('user_id' => me()->id))}}">My Pledges</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">Recommendations</li>
							<li><a tabindex="-1" href="{{route('recommendationsbyuser', array('user_id' => me()->id))}}">Recommendations I've Written For Others</a></li>
							<li><a tabindex="-1" href="{{route('recommendationsforuser', array('user_id' => me()->id))}}">Recommendations Others Wrote for Me</a></li>
							<li class="divider"></li>
							<li><a tabindex="-1" href="{{route('logout')}}">Logout</a></li>
						</ul>
					</li>
					@endif
				</ul>
			</div>
		</div>
	</div>

	<div class="container">

		@if (Session::has('error'))
		<div class="alert alert-danger" id="errors">
			{{ Session::get('error') }}
		</div>
		@endif

		@if (Session::has('msg'))
		<div class="alert alert-info" id="messages">
			{{ Session::get('msg') }}
		</div>
		@endif

		<div id="content">
		@yield('content')
		</div>

	</div>
</body>
</html>
<script>
$(document).ready(function()
{
	$('.date-widget').datepicker();
	$('*').tooltip();
	$('.wysiwyg').wysihtml5();
});

// CUSTOM JAVASCRIPT CODE
function doconfirm(prompt)
{
	if (typeof(prompt) == 'undefined') prompt = 'Are you sure you want to do that?';
	return confirm(prompt);
}

</script>
<?php
Session::forget('status');
Session::forget('message');
?>
