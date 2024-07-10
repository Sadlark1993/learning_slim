<?php

declare(strict_types=1);

use App\Database;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

//dependency injection container
//use DI\Container;

// the class files we are going to use will be loaded automatically
require dirname(__DIR__) . '/vendor/autoload.php';

// I'm not using dependency injection container 
//$container = new Container;
//AppFactory::setContainer($container);

$app = AppFactory::create();

$app->get('/api/products', function (ServerRequestInterface $request, ResponseInterface $response) {

  $database = new Database;
  $repository = new App\Repositories\ProductRepository($database);
  $data = $repository->getAll();

  $body = json_encode($data);
  $response->getBody()->write($body);
  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
