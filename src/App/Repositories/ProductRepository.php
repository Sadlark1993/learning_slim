<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class ProductRepository
{
  public function __construct(private Database $database = new Database())
  {
  }

  public function getAll(): array|bool
  {
    $pdo = $this->database->getConnection();

    $stmt = $pdo->query('SELECT * FROM product');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getById(int $id): array|bool
  {
    $query = 'SELECT *
              FROM product
              WHERE id = :id';
    $pdo = $this->database->getConnection();
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
