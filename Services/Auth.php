<?php
namespace Blog\Services;

use Blog\Request;
use Blog\Services\User;
use Blog\Services\Token;

class Auth {
  public static function sign_up(string $name, string $email, string $password): array {
    $user = User::create($name, $email, $password);

    return $user;
  }

  public static function sign_in(array $body): array {
    $user = User::get_user($body["email"], $body["password"]);
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
