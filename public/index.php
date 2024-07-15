<?php

declare(strict_types=1);

use App\Controllers\ProductIndex;
use Slim\Factory\AppFactory;
use App\Database;
use App\Repositories\ProductRepository;
use App\Middleware\AddJsonResponseHeader;
//use App\Middleware\GetProduct;

use App\Controllers\Products;

//dependency injection container
//use DI\Container;

// the class files we are going to use will be loaded automatically
require_once dirname(__DIR__) . '/vendor/autoload.php';
//require_once dirname(__DIR__) . '/config/definitions.php';

// I'm not using dependency injection container
//$container = new Container;
//AppFactory::setContainer($container);

$app = AppFactory::create();

// to get body in the requisitions and decode
$app->addBodyParsingMiddleware();

// error handling
$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

//sets the header of the responses through middleware
$app->add(new AddJsonResponseHeader);

$database = new Database();
$app->get('/api/products', new ProductIndex);
$app->get('/api/products/{id:[0-9]+}', Products::class . ':show')->add(new App\Middleware\GetProduct(new ProductRepository($database)));
$app->post('/api/products', [Products::class, 'create']);
$app->patch('/api/products/{id:[0-9]+}', Products::class . ':update')->add(new App\Middleware\GetProduct(new ProductRepository($database)));
$app->delete('/api/products/{id:[0-9]+}', Products::class . ':delete')->add(new App\Middleware\GetProduct(new ProductRepository($database)));
$app->run();
