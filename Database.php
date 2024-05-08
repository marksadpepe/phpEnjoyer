<?php
namespace Blog;

class Database {
  private string $host;
  private string $username;
  private string $password;
  private string $db_name;
  private int $port;
  private $conn;

  public function __construct(string $host, string $username, string $password, string $db_name, int $port) {
    $this->host = $host;
    $this->username = $username;
    $this->password = $password;
    $this->db_name = $db_name;
    $this->port = $port;

    $this->connect();
  }

  public function __destruct() {
    $this->disconnect();
  }

  public function query(string $query): mixed {
    $result = $this->conn->query($query);
    return $result;
  }

  private function connect(): void {
    $this->conn = new \mysqli($this->host, $this->username, $this->password, $this->db_name, $this->port);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }

  private function disconnect(): void {
    if ($this->conn) {
      $this->conn->close();
    }
  }
}
?>
