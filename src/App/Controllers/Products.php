<?php

declare(strict_types=1);


namespace App\Controllers;

use App\Database;
use App\Repositories\ProductRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Valitron\Validator;

class Products
{
  private ProductRepository $repository;
  private Validator $validator;

  public function __construct()
  {
    $this->repository = new ProductRepository(new Database());
    $this->validator = new Validator();
    $this->validator->mapFieldsRules([
      'name' => ['required'],
      'size' => ['required', 'integer', ['min', 1]]
    ]);
  }

  public function show(Request $request, Response $response): Response
  {
    $product = $request->getAttribute('product');
    $response->getBody()->write(json_encode($product));
    return $response;
  }

  public function create(Request $request, Response $response): Response
  {
    $body = $request->getParsedBody();

    // data validation
    $this->validator = $this->validator->withData($body);
    if (!$this->validator->validate()) {
      $response->getBody()->write(json_encode($this->validator->errors()));
      return $response->withStatus(422);
    }

    $id = $this->repository->create($body);

    $body = json_encode([
      'message' => 'Product created',
      'id' => $id
    ]);

    // encoding back
    $response->getBody()->write($body);
    return $response->withStatus(201);
  }

  public function update(Request $request, Response $response): Response
  {
    $product = $request->getAttribute('product');
    $id = $product['id'];
    $body = $request->getParsedBody();

    // data validation
    $this->validator = $this->validator->withData($body);
    if (!$this->validator->validate()) {
      $response->getBody()->write(json_encode($this->validator->errors()));
      return $response->withStatus(422);
    }

    $rows = $this->repository->update((int) $id, $body);

    $body = json_encode([
      'message' => 'Product updated. ' . $rows . ' changed.',
      'id' => $id
    ]);

    // encoding back
    $response->getBody()->write($body);
    return $response;
  }

  public function delete(Request $request, Response $response, array $args): Response
  {
    $rows = $this->repository->delete($args['id']);

    $body = json_encode([
      'message' => 'Product deleted',
      'rows' => $rows
    ]);

    $response->getBody()->write($body);
    return $response->withStatus(204);
  }
}
