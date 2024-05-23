<?php
namespace Blog;

class Response {
  private int $status_code;
  private array $headers = [];
  private array $cookies = [];
  private ?string $content = null;

  public function __construct(int $status_code = 200) {
    $this->status_code = $status_code;
  }

  public static function json(?int $status_code=null, ?array $data=null): Response {
    return (new Response())->with_json($status_code, $data);
  }

  public function with_json(?int $status_code=null, ?array $data=null): Response {
    $this->headers[] = "Content-Type: application/json; charset=UTF-8";
    $this->content = $data ? json_encode($data) : "{}";
    $this->status_code = $status_code ? $status_code : $this->status_code;
    return $this;
  }

  public function with_cookie(string $name, mixed $value): Response {
    $this->cookies[$name] = $value;
    return $this;
  }

  public function send(): void {
    global $TOKEN_TTL;

    http_response_code($this->status_code);

    foreach($this->headers as $h) {
      header($h);
    }

    foreach($this->cookies as $name=>$value) {
      $cookie_header = "Set-Cookie: {$name}={$value}; Path=/; HttpOnly";
      if ($name == "refresh_token") {
        $max_age = $TOKEN_TTL * 24 * 60 * 60;
        $cookie_header .= "; Max-Age={$max_age}";
      }
      header($cookie_header);
    }

    echo $this->content;
  }
}
?>
