<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("<h1>StarFruit</h1>");
    return $response;
});

require __DIR__ . '/routes/contacto.routes.php';
require __DIR__ . '/routes/usuario.routes.php';

$app->run();
