<?php
namespace Blog\Services;

class User {
  private static string $table_name = "users";

  public static function create(string $name, string $email, string $password): array {
    global $db;

    $res = $db->query("select * from users where email = '{$email}'");
    $candidate = $res->fetch_assoc();
    if ($candidate) {
      throw new \Exception("User with such email already exists");
    }

    $id = $db->query("select id from users order by id desc limit 1")->fetch_assoc()["id"] + 1;
    $pwd_hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "insert into " . self::$table_name . "(id, fullName, email, password) values({$id}, '{$name}', '{$email}', '{$pwd_hash}')";
    $db->query($query);

    return [
      "id" => $id,
      "name" => $name,
      "email" => $email,
    ];
  }

  public static function get_user(string $email, string $password): array {
    global $db;

    $user = $db->query("select * from " . self::$table_name . " where email = '{$email}'")->fetch_assoc();
    if (!$user) {
      throw new \Exception("404:User with such email does not exists");
    }

    if (!password_verify($password, $user["password"])) {
      throw new \Exception("400:Incorrect password");
    }

    unset($user["password"]);
    return $user;
  }
  
  public static function get_users(): array {
    global $db;

    $idx = 0;
    $users = [];
    $qresult = $db->query("select * from " . self::$table_name);

    foreach($qresult as $user) {
      $users[$idx] = $user;
      $idx++;
    }

    return $users;
  }
}
?>
