<?php

require '../vendor/autoload.php';

$app = new \WildWolf\Application(['settings' => require __DIR__ . '/../config/config.php']);
$app->initialize();
$app->run();
