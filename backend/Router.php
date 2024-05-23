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
    $pattern = preg_replace("/{([a-zA-Z]+)}/", "(?P<\\1>[^/]+)", $uri);
    $pattern = "/^" . str_replace("/", "\\/", $pattern) . "$/";

    if (!isset($this->routes[$pattern])) {
      $this->routes[$pattern] = [];
    }

    $this->routes[$pattern][$method] = [$mdws, $handler];
  }

  public function get(string $uri, ?array $mdws, mixed $handler): void {
    $this->add("GET", $uri, $mdws, $handler);
  }

  public function post(string $uri, ?array $mdws, mixed $handler): void {
    $this->add("POST", $uri, $mdws, $handler);
  }

  public function put(string $uri, ?array $mdws, mixed $handler): void {
    $this->add("PUT", $uri, $mdws, $handler);
  }

  public function delete(string $uri, ?array $mdws, mixed $handler): void {
    $this->add("DELETE", $uri, $mdws, $handler);
  }

  public function route(Request $req): ?Response {
    $find_uri = false;
    $method = $req->get_method();
    $uri = $req->get_uri();

    foreach($this->routes as $pattern=>$values) {
      if (preg_match($pattern, $uri, $matches)) {
        $find_uri = true;

        if (!isset($this->routes[$pattern][$method])) {
          return Response::json(405, ["error" => "Method Not Allowed"]);
        }

        [$mdws, $handler] = $this->routes[$pattern][$method];
        if ($mdws) {
          foreach($mdws as $mdw) {
            $mdw_handler = $this->middleware_namespace . "\\" . $mdw;
            $res = call_user_func($mdw_handler, $req);
            if ($res) {
              return $res;
            }
          }
        }

        $handler = $this->namespace . "\\" . $handler;
        return call_user_func($handler, $req, $matches);
      }
    }

    if (!$find_uri) {
      return Response::json(404, ["error" => "Page Not Found"]);
    }
  }
}
?>
