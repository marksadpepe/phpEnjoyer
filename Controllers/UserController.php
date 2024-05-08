<?php
namespace Blog\Controllers;

use Blog\Request;
use Blog\Response;
use Blog\Services\User;

class UserController {
  public static function get_users(?Request $req): Response {
    try {
      $users = User::get_users();
      return Response::json(200, $users);
    } catch (\Exception $e) {
      return Response::json(500, ["error" => "Internal Server Error"]);
    }
  }

  public static function create_user(Request $req): Response {
    $body = $req->get_body();
    try {
      $user = User::create($body["name"], $body["email"], $body["password"]);
      return Response::json(201, $user);
    } catch (\Exception $e) {
      return Response::json(409, ["error" => $e->getMessage()]);
    }
  }
}
?>
