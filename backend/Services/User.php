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
    global $redis;
    global $REDIS_TTL;

    $key = "user:{$id}";
    $raw = $redis->get($key);
    if ($raw) {
      return json_decode($raw, true);
    }

    $query = "select id, fullName, email, created from " . self::$table_name . " where id = {$id}";
    $user = $db->query($query)["result"]->fetch_assoc();

    if (!$user) {
      throw new \Exception("404:User with such email does not exists");
    }

    $user["created"] = strtotime($user["created"]);
    $redis->set($key, json_encode($user));
    $redis->expire($key, $REDIS_TTL);

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
    global $redis;
    global $REDIS_TTL;

    $raw = $redis->get("users");
    if ($raw) {
      return json_decode($raw, true);
    }

    $idx = 0;
    $users = [];
    $query = "select " . self::$table_name . ".id, fullname, email, role, created from " . self::$table_name . " join users_roles as ur on users.id = ur.userid join roles on roles.id = ur.roleid order by users.id";
    $qresult = $db->query($query)["result"];

    foreach($qresult as $user) {
      $user["created"] = strtotime($user["created"]);
      $users[$idx] = $user;
      $idx++;
    }

    $redis->set("users", json_encode($users));
    $redis->expire("users", $REDIS_TTL);

    return $users;
  }

  public static function update_user(int $id, string $full_name, string $email): array {
    global $db;

    $query = "update " . self::$table_name . " set fullName = '{$full_name}', email = '{$email}' where id = {$id}";
    $qres = $db->query($query);
    if ($qres["rows"] < 1) {
      throw new \Exception("404:User with such ID does not exists");
    }

    $user = $db->query("select id, fullName, email, created from " . self::$table_name . " where id = {$id}")["result"]->fetch_assoc();
    $user["created"] = strtotime($user["created"]);

    return $user;
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
