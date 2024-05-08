<?php
namespace Blog\Middlewares;

use Blog\Request;
use Blog\Response;
use Blog\Services\Signer;

class AuthMiddleware {
  public static function handle(Request $req): ?Response {
    $key = "HTTP_AUTHORIZATION";
    $headers = $req->get_headers();
    if (!array_key_exists($key, $headers)) {
      return Response::json(401, ["error" => "Unauthorized"]);
    }

    if (strpos($headers[$key], "Bearer") !== 0) {
      return Response::json(401, ["error" => "Unauthorized"]);
    }

    $token = explode(" ", $headers[$key])[1];
    if (!Signer::verify($token)) {
      return Response::json(401, ["error" => "Unauthorized"]);
    }

    return null;
  }
}
