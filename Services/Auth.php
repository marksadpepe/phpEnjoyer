<?php
namespace Blog\Services;

use Blog\Request;
use Blog\Services\User;
use Blog\Services\Token;

class Auth {
  public static function sign_up(array $body): array {
    $user = User::create(
      $body["name"],
      $body["email"],
      $body["password"],
      $body["role"]
    );
    return $user;
  }

  public static function sign_in(array $body): array {
    $user = User::get_user_by_email($body["email"]);
    if (!password_verify($body["password"], $user["password"])) {
      throw new \Exception("400:Incorrect password");
    }


    unset($user["password"]);
    $tokens = Token::generate_tokens($user);
    Token::save_token($user["id"], $tokens["refresh_token"]);

    return [
      "user" => $user,
      "tokens" => $tokens
    ];
  }

  public static function sign_out(string $token): void {
    Token::delete_token($token);
  }
}
?>
