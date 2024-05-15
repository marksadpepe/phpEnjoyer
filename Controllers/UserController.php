<?php
namespace Blog\Controllers;

use Blog\Request;
use Blog\Response;
use Blog\Services\User;

class UserController {
  public static function get_user(Request $req, array $route_params): Response {
    try {
      $user = User::get_user_by_id($route_params["uid"]);
      return Response::json(200, $user);
    } catch (\Exception $e) {
      [$status_code, $err_msg] = explode(":", $e->getMessage());
      return Response::json((int)$status_code, ["error" => $err_msg]);
    }
  }

  public static function get_users(Request $req, array $route_params): Response {
    try {
      $users = User::get_users();
      return Response::json(200, $users);
    } catch (\Exception $e) {
      return Response::json(500, ["error" => "Internal Server Error"]);
    }
  }

  public static function create_user(Request $req, array $route_params): Response {
    $body = $req->get_body();
    try {
      $user = User::create($body["name"], $body["email"], $body["password"], $body["role"]);
      return Response::json(201, $user);
    } catch (\Exception $e) {
      return Response::json(409, ["error" => $e->getMessage()]);
    }
  }
}
?>
