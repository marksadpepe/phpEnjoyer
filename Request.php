<?php
namespace Blog;

class Request {
  private string $method;
  private string $uri;
  private ?array $body = null;
  private array $headers = [];
  private array $cookies = [];

  public function __construct() {
    $this->method = $_SERVER["REQUEST_METHOD"];
    $this->uri = $_SERVER["REQUEST_URI"];
  }

  public function get_method(): string {
    return $this->method;
  }

  public function get_uri(): string {
    return $this->uri;
  }

  public function get_headers(): array {
    foreach($_SERVER as $key=>$value) {
      if (strpos($key, "HTTP_") === 0) {
        $this->headers[$key] = $value;
      }
    }

    return $this->headers;
  }

  public function get_cookies(): array {
    foreach($_COOKIE as $name=>$value) {
      $this->cookies[$name] = $value;
    }

    return $this->cookies;
  }

  public function get_body(): ?array {
    if (is_null($this->body)) {
      $this->body = json_decode(file_get_contents("php://input"), true) ?: null;
    }
    return $this->body;
  }
}
?>
