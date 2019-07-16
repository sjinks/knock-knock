<!DOCTYPE html>
<html lang="uk">
<head>
	<meta charset="utf-8"/>
	<title>Миротворець</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"/>
<?php if (!empty($header_css) && is_array($header_css)) : ?>
<?php foreach ($header_css as $src) : ?>
	<link rel="stylesheet" href="<?=$src;?>"/>
<?php endforeach; ?>
<?php endif; ?>
	<link rel="stylesheet" href="/css/style.css?v=14"/>
	<link rel="icon" href="/img/icon32.png" sizes="32x32"/>
	<link rel="icon" href="/img/icon192.png" sizes="192x192"/>
	<link rel="apple-touch-icon" href="/img/icon180.png"/>
	<meta name="msapplication-TileImage" content="/img/icon270.png"/>
<?php if (!empty($header_js) && is_array($header_js)) : ?>
<?php foreach ($header_js as $src) : ?>
	<script src="<?=$src;?>"></script>
<?php endforeach; ?>
<?php endif; ?>
</head>
<body>
	<div id="container" class="d-flex flex-column">
		<header id="header" class="py-3 text-center">
			<h1>«МИРОТВОРЕЦЬ»</h1>
			<h2>ЦЕНТР ДОСЛІДЖЕННЯ ОЗНАК ЗЛОЧИНІВ ПРОТИ НАЦІОНАЛЬНОЇ БЕЗПЕКИ УКРАЇНИ, МИРУ, БЕЗПЕКИ ЛЮДСТВА ТА МІЖНАРОДНОГО ПРАВОПОРЯДКУ</h2>
		</header>

		<nav id="nav" class="navbar navbar-expand-md navbar-dark mb-3 mx-0">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
				<ul class="navbar-nav">
					<li class="nav-item"><a href="/" class="nav-link">Головна</a></li>
					<li class="nav-item"><a href="https://myrotvorets.center/" class="nav-link">Миротворець</a></li>
					<li class="nav-item"><a href="https://myrotvorets.center/criminals/" class="nav-link">Чистилище</a></li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarInteraction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Взаємодія</a>
						<div class="dropdown-menu" aria-labelledby="navbarInteraction">
							<a class="dropdown-item" href="https://myrotvorets.center/about/">Про Центр</a>
							<a class="dropdown-item" href="https://myrotvorets.center/about/zlochini/">Перелік злочинів, що досліджуються Центром</a>
							<a class="dropdown-item" href="https://myrotvorets.center/contacts/">Про взаємодію та співпрацю з Центром</a>
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
