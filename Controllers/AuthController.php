<?php
namespace Blog\Controllers;

use Blog\Request;
use Blog\Response;
use Blog\Services\Auth;

class AuthController {
  public static function sign_up(Request $req): Response {
    $body = $req->get_body();
    try {
      $data = Auth::sign_up($body["name"], $body["email"], $body["password"]);
      return Response::json(201, $data);
    } catch (\Exception $e) {
      return Response::json(409, ["error" => $e->getMessage()]);
    }
  }

  public static function sign_in(Request $req): Response {
    $body = $req->get_body();
    try {
      $data = Auth::sign_in($body);
      return Response::json(200, $data)->with_cookie("refresh_token", $data["tokens"]["refresh_token"]);
    } catch (\Exception $e) {
      [$status_code, $err_msg] = explode(":", $e->getMessage());
      return Response::json((int)$status_code, ["error" => $err_msg]);
    }
  }
}
?>
