<?php
namespace Blog\Services;

use Blog\Services\Role;
use Blog\Services\UserRole;

class User {
  private static string $table_name = "users";

  public static function create(string $name, string $email, string $password, string $role): array {
    global $db;

    $res = $db->query("select * from users where email = '{$email}'")["result"];
    $candidate = $res->fetch_assoc();
    if ($candidate) {
      throw new \Exception("User with such email already exists");
    }

    $role_id = Role::get_role_by_name($role)["id"];

    $id = $db->query("select id from users order by id desc limit 1")["result"]->fetch_assoc()["id"] + 1;
    $pwd_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "insert into " . self::$table_name . "(id, fullName, email, password) values({$id}, '{$name}', '{$email}', '{$pwd_hash}')";
    $db->query($query);

    UserRole::create_user_role($id, $role_id);

    return [
      "id" => $id,
      "name" => $name,
      "email" => $email,
      "role" => $role
    ];
  }

  public static function get_user_by_id(int $id): array {
    global $db;

    $query = "select id, fullName, email, created from " . self::$table_name . " where id = {$id}";
    $user = $db->query($query)["result"]->fetch_assoc();

    if (!$user) {
      throw new \Exception("404:User with such email does not exists");
    }

    $user["created"] = strtotime($user["created"]);

    return $user;
  }

  public static function get_user_by_email(string $email): array {
    global $db;

    $query = "select " . self::$table_name . ".id, fullname, email, password, role, created from " . self::$table_name . " join users_roles as ur on users.email = '{$email}' and users.id = ur.userid join roles on roles.id = ur.roleid order by users.id";
    $user = $db->query($query)["result"]->fetch_assoc();

    if (!$user) {
      throw new \Exception("404:User with such email does not exists");
    }

    $user["created"] = strtotime($user["created"]);

    return $user;
  }
  
  public static function get_users(): array {
    global $db;

    $idx = 0;
    $users = [];
    $query = "select " . self::$table_name . ".id, fullname, email, role, created from " . self::$table_name . " join users_roles as ur on users.id = ur.userid join roles on roles.id = ur.roleid order by users.id";
    $qresult = $db->query($query)["result"];

    foreach($qresult as $user) {
      $user["created"] = strtotime($user["created"]);
      $users[$idx] = $user;
      $idx++;
    }

    return $users;
  }

  public static function delete_user_by_id(int $id): void {
    global $db;

    $query = "delete from " . self::$table_name . " where id = {$id}";
    $qres = $db->query($query);

    if ($qres["rows"] < 1) {
      throw new \Exception("404:User with such id does not exists");
    }
  }
}
?>
