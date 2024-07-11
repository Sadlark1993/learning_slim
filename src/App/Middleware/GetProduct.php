<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Repositories\ProductRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\MessageInterface as Response;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

class GetProduct
{
  public function __construct(private ProductRepository $repository)
  {
  }

  // if the object (class instance) is called as if its a function, this method will be executed.
  public function __invoke(Request $request, RequestHandler $handler): Response
  {
    // this block is to take the argument of the request
    $context = RouteContext::fromRequest($request);
    $route = $context->getRoute();
    $id = $route->getArgument('id');

    // get from db by id
    $product = $this->repository->getById((int) $id);

    // not found exeption
    if ($product === false) {
      throw new HttpNotFoundException($request, message: 'product not found');
    }

    $request = $request->withAttribute('product', $product);

    return $handler->handle($request);
  }
}
