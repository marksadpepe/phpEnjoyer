<?php
namespace Blog\Services;

class UserRole {
  private static string $table_name = "users_roles";

  public static function create_user_role(int $user_id, int $role_id): void {
    global $db;

    $db->query("insert into " . self::$table_name . "(userId, roleId) values({$user_id}, {$role_id})");
  }
}
?>
