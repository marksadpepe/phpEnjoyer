<?php
namespace Blog\Services;

use Blog\Services\Signer;

class Token {
  private static string $table_name = "tokens";

  public static function generate_tokens(array $payload): array {
    global $TOKEN_TTL;

    $access_token = Signer::sign($payload, $TOKEN_TTL . "m");
    $refresh_token = Signer::sign($payload, $TOKEN_TTL . "d");

    return [
      "access_token" => $access_token,
      "refresh_token" => $refresh_token
    ];
  }

  public static function save_token(int $user_id, string $token) {
    global $db;

    $res = $db->query("select * from " . self::$table_name . " where userId = {$user_id}")->fetch_assoc();
    if ($res) {
      $db->query("update " . self::$table_name . " set token = '{$token}' where userId = {$user_id}");
      return;
    }

    $db->query("insert into " . self::$table_name . "(token, userId) values('{$token}', {$user_id})");
    return;
  }

  public static function delete_token(string $token) {
    global $db;

    $db->query("delete from " . self::$table_name . " where token = '{$token}'");

    return;
  }
}
?>
