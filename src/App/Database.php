<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database
{
  public function getConnection(): PDO
  {
    $dsn = "pgsql:host=192.168.115.144;port=5432;dbname=seedlingNursery";
    $pdo = new PDO($dsn, 'postgres', '1234', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    return $pdo;
  }
}
