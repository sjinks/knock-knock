<!DOCTYPE html>
<html lang="uk">
<head>
	<meta charset="utf-8"/>
	<title>Миротворец</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha256-rr9hHBQ43H7HSOmmNkxzQGazS/Khx+L8ZRHteEY1tQ4=" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" integrity="sha256-7TyXnr2YU040zfSP+rEcz29ggW4j56/ujTPwjMzyqFY=" crossorigin="anonymous"/>
	<link rel="stylesheet" href="/css/style.css?v=4"/>
	<link rel="icon" href="/img/icon32.png" sizes="32x32"/>
	<link rel="icon" href="/img/icon192.png" sizes="192x192"/>
	<link rel="apple-touch-icon-precomposed" href="/img/icon180.png"/>
	<meta name="msapplication-TileImage" content="/img/icon270.png"/>
<?php if (!empty($header_js) && is_array($header_js)) : ?>
<?php foreach ($header_js as $src) : ?>
	<script src="<?=$src;?>"></script>
<?php endforeach; ?>
<?php endif; ?>
</head>
<body class="container">
	<header id="header" class="row align-items-center">
		<div class="col-sm-3 col-md-2 hidden-xs-down">
			<a href="/"><img src="/img/logo.png" width="109" height="109"/></a>
		</div>
		<div class="col-12 col-sm-9 col-md-10">
			<h1>«МИРОТВОРЕЦ»</h1>
			<h2>ЦЕНТР ИССЛЕДОВАНИЯ ПРИЗНАКОВ ПРЕСТУПЛЕНИЙ ПРОТИВ НАЦИОНАЛЬНОЙ БЕЗОПАСНОСТИ УКРАИНЫ, МИРА, БЕЗОПАСНОСТИ ЧЕЛОВЕЧЕСТВА И МЕЖДУНАРОДНОГО ПРАВОПОРЯДКА</h2>
		</div>
	</header>

	<nav id="nav" class="navbar navbar-toggleable-sm navbar-inverse mb-3" style="background-color: #3B5998">
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav">
				<li class="nav-item"><a href="/" class="nav-link">Главная</a></li>
				<li class="nav-item"><a href="https://myrotvorets.center/" class="nav-link">Миротворец</a></li>
				<li class="nav-item"><a href="https://myrotvorets.center/criminals/" class="nav-link">Чистилище</a></li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarInteraction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Взаимодействие</a>
					<div class="dropdown-menu" aria-labelledby="navbarInteraction">
						<a class="dropdown-item" href="https://myrotvorets.center/about/">О Центре</a>
						<a class="dropdown-item" href="https://myrotvorets.center/about/zlochini/">Перечень исследуемых преступлений</a>
						<a class="dropdown-item" href="https://myrotvorets.center/contacts/">О взаимодействии и сотрудничестве</a>
					</div>
				</li>
			</ul>
		</div>
	</nav>

	<noscript>
		<div class="alert alert-danger" role="alert">
			Для нормальної роботи сайту потрібно JavaScript.
		</div>
	</noscript>
