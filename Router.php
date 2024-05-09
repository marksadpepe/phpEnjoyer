<?php
namespace Blog;

use Blog\Request;
use Blog\Response;

class Router {
  public array $routes;
  public string $namespace;
  public string $middleware_namespace;

  public function __construct() {
    $this->routes = [];
  }

  public function set_namespace(string $namespace): void {
    $this->namespace = $namespace;
  }

  public function set_middleware_namespace(string $namespace): void {
    $this->middleware_namespace = $namespace;
  }

  public function add(string $method, string $uri, ?array $mdws, mixed $handler): void {
    if (!array_key_exists($uri, $this->routes)) {
      $this->routes[$uri] = [];
    }

    $this->routes[$uri][$method] = [$mdws, $handler];
  }

  public function get(string $uri, ?array $mdws, mixed $handler): void {
    $this->add("GET", $uri, $mdws, $handler);
  }

  public function post(string $uri, ?array $mdws, mixed $handler): void {
    $this->add("POST", $uri, $mdws, $handler);
  }

  public function route(Request $req): ?Response {
    $method = $req->get_method();
    $uri = $req->get_uri();

    if (!array_key_exists($uri, $this->routes)) {
      header("Content-Type: text/html");
      http_response_code(404);
      echo "<h1>! Page Not Found !</h1>";
      return null;
    }

    if (!array_key_exists($method, $this->routes[$uri])) {
      header("Content-Type: text/html");
      http_response_code(405);
      echo "<h1>! Method Not Allowed !</h1>";
      return null;
    }

    [$mdws, $handler] = $this->routes[$uri][$method];
    if ($mdws) {
      foreach ($mdws as $mdw) {
        $mdw_handler = $this->middleware_namespace . "\\" . $mdw;
        $res = call_user_func($mdw_handler, $req);
        if ($res) {
          return $res;
        }
      }
    }
    $handler = $this->namespace . "\\" . $handler;

    return call_user_func($handler, $req);
  }
}
?>
