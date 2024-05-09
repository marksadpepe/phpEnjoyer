<?php
namespace Blog\Services;

class Role {
  private static string $table_name = "roles";

  public static function get_role_by_id(int $id): array {
    global $db;

    $role = $db->query("select * from " . self::$table_name . " where id = {$id}")->fetch_assoc();

    return $role;
  }

  public static function get_role_by_name(string $role_name): array {
    global $db;

    $role = $db->query("select * from " . self::$table_name . " where role = '{$role_name}'")->fetch_assoc();

    return $role;
  }
}
?>
