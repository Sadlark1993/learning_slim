<?php

declare(strict_types=1);

namespace App;

use Config\Definitions;
use PDO;

class Database
{
  public function __construct(
    private string $host = Definitions::HOST,
    private string $port = Definitions::PORT,
    private string $dbName = Definitions::DB_NAME,
    private string $user = Definitions::USER,
    private string $password = Definitions::PASSWORD
  ) {
  }

  public function getConnection(): PDO
  {
    $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbName";
    return new PDO($dsn, $this->user, $this->password, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
  }
}
