<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\MessageInterface as Response;

class AddJsonResponseHeader
{
  // if the object (class instance) is called as if its a function, this method will be executed.
  public function __invoke(Request $request, RequestHandler $handler): Response
  {
    $response = $handler->handle($request);
    return $response->withHeader('Content-Type', 'application/json');
  }
}
