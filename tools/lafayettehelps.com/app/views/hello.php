<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lafayette Helps</title>
    <style>
        @import url(//fonts.googleapis.com/css?family=Lato:300,400,700);

        body {
            margin:0;
            font-family:'Lato', sans-serif;
            text-align:center;
            color: #999;
        }

        .welcome {
           width: 400px;
           height: 400px;
           position: absolute;
           left: 50%;
           top: 40%;
           margin-left: -200px;
           margin-top: -200px;
        }

        a, a:visited {
            color:#FF5949;
            text-decoration:none;
        }

        a:hover {
            text-decoration:underline;
        }

        ul li {
            display:inline;
            margin:0 1.2em;
        }

        p {
            margin:2em 0;
            color:#555;
        }
    </style>
</head>
<body>
    <div class="welcome">
        <a href="http://laravel.com" title="Laravel PHP Framework"><img src="/images/logo.png"></a>
        <h1>You have arrived.</h1>
        An initial view has been created at <a href="/users">/users</a>
		<ul>

		<?php foreach (Array('users','requests') as $item) : ?>
		<li><a href="/<?php print $item; ?>">/<?php print $item; ?></a></li>
		<?php endforeach; ?>

		</ul>
    </div>
</body>
</html>
