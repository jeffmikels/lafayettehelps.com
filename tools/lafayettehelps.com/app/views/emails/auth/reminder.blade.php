<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>LafayetteHelps.com Password Reset</h2>

		<div>
			
			<p>Someone (hopefully you) requested a new password for the account linked to <code>{{ $user->email }}</code>.</p>
			<p>
				If it wasn't you, don't worry. Your password is currently unchanged,
				but if it was you, you can create a new password by following these steps:
			</p>
			<ul>
				<li>Visit this page: <a href="{{ URL::to('password/reset', array($token)) }}">{{ URL::to('password/reset', array($token)) }}</a>.</li>
				<li>Fill out the form using this email address {{ $user->email }} and your new password.</li>
				<li>That's it. Did you think it would be more difficult?</li>
			</ul>
		</div>
	</body>
</html>