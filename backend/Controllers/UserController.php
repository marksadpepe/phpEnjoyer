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
      [$status_code, $err_msg] = explode(":", $e->getMessage());
      return Response::json((int)$status_code, ["error" => $err_msg]);
    }
  }

  public static function create_user(Request $req, array $route_params): Response {
    $body = $req->get_body();
    try {
      $user = User::create($body["name"], $body["email"], $body["password"], $body["role"]);
      return Response::json(201, $user);
    } catch (\Exception $e) {
      [$status_code, $err_msg] = explode(":", $e->getMessage());
      return Response::json((int)$status_code, ["error" => $err_msg]);
    }
  }

  public static function update_user(Request $req, array $route_params): Response {
    $body = $req->get_body();
    try {
      $user = User::update_user($route_params["uid"], $body["fullName"], $body["email"]);
      return Response::json(200, $user);
    } catch (\Exception $e) {
      [$status_code, $err_msg] = explode(":", $e->getMessage());
      return Response::json((int)$status_code, ["error" => $err_msg]);
    }
  }

  public static function delete_user(Request $req, array $route_params): Response {
    try {
      User::delete_user_by_id($route_params["uid"]);
      // status code should be 204 but I want to keep the response body, even if its empty
      return Response::json(200, []);
    } catch (\Exception $e) {
      [$status_code, $err_msg] = explode(":", $e->getMessage());
      return Response::json((int)$status_code, ["error" => $err_msg]);
    }
  }
}
?>
