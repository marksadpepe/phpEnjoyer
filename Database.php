<?php
namespace Wblog\DB;

class Database {
  private string $host;
  private int $port;
  private string $username;
  private string $password;
  private string $dbName;
  private $conn;

  public function __construct(string $host, int $port, string $username, string $password, string $dbName) {
    $this->host = $host;
    $this->port = $port;
    $this->username = $username;
    $this->password = $password;
    $this->dbName = $dbName;
    $this->connect();
  }

  public function __destruct() {
    $this->disconnect();
  }

  public function connect(): void {
    $this->conn = new \mysqli($this->host, $this->username, $this->password, $this->dbName, $this->port);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }

    echo "Connected to the {$this->dbName} database\n";
  }

  public function disconnect():void {
    if ($this->conn) {
      $this->conn->close();
    }
  }
}
?>
