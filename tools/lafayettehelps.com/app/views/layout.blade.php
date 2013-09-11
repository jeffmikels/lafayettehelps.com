<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			@import url(//fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic);
			body {font-family: Lato;}
			label {font-weight:300;display:inline-block;width:200px;text-align:right;margin-right:10px;font-size:18pt;}
			
			input[type=text],
			input[type=email],
			input[type=password]
			{width:300px; text-align:left; margin-left:20px;font-size:18pt;padding:10px;border-radius:6px;}
			
			pre.debug {font-size:8px;}
		</style>
	</head>
	<body>
		<h1><a href='//test.lafayettehelps.com'>test.lafayettehelps.com</a></h1>

		@yield('content')

	</body>
</html>