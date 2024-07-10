<?php

declare(strict_types=1);

use App\Database;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Config\Definitions;
use App\Repositories\ProductRepository;

//dependency injection container
//use DI\Container;

// the class files we are going to use will be loaded automatically
require_once dirname(__DIR__) . '/vendor/autoload.php';
//require_once dirname(__DIR__) . '/config/definitions.php';

// I'm not using dependency injection container
//$container = new Container;
//AppFactory::setContainer($container);

$app = AppFactory::create();

$app->get('/api/products', function (ServerRequestInterface $request, ResponseInterface $response) {

  $database = new Database(Definitions::HOST, Definitions::PORT, Definitions::DB_NAME, Definitions::USER, Definitions::PASSWORD);
  $repository = new ProductRepository($database);
  $data = $repository->getAll();

  $body = json_encode($data);
  $response->getBody()->write($body);
  return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/products/{id:[0-9]+}', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
  $id = $args['id'];
  $repository = new ProductRepository();
  $product = $repository->getById((int) $id);

  // not found exeption
  if ($product === false) {
    throw new \Slim\Exception\HttpNotFoundException($request, message: 'product not found');
  }

  $response->getBody()->write(json_encode($product));
  return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
