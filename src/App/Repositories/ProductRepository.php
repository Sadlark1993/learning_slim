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

  public function create(array $data): string
  {
    $query = 'INSERT INTO product (name, description, size)
              VALUES (:name, :description, :size)';

    $pdo = $this->database->getConnection();
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);

    if (empty($data['description'])) {
      $stmt->bindValue(':description', null, PDO::PARAM_NULL);
    } else {
      $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
    }
    $stmt->bindValue(':size', $data['size'], PDO::PARAM_INT);
    $stmt->execute();
    return $pdo->lastInsertId();
  }

  public function update(int $id, array $data): int
  {
    $query = 'UPDATE product
              SET name = :name,
                  description = :description,
                  size = :size
              WHERE id = :id';

    $pdo = $this->database->getConnection();
    $stmt = $pdo->prepare($query);

    // biding values
    $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
    if (empty($data['description'])) {
      $stmt->bindValue(':description', null, PDO::PARAM_NULL);
    } else {
      $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
    }
    $stmt->bindValue(':size', $data['size'], PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->rowCount();
  }

  public function delete(string $id): int
  {
    $query = 'DELETE FROM product
              WHERE id = :id';
    $pdo = $this->database->getConnection();
    $stmt = $pdo->prepare($query);

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return  $stmt->rowCount();
  }
}
