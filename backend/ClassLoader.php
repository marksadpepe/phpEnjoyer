<?php
namespace Blog;

class ClassLoader {
  private static array $map;

  public static function init(array $map): void {
    self::$map = $map;

    $callback = function ($class_name) {
      foreach(self::$map as $prefix=>$path) {
        if (strpos($class_name, $prefix) == 0) {
          $file = str_replace("\\", "/", substr($class_name, strlen($prefix) - 1)) . ".php";
          require_once($path . $file);
          break;
        }
      }
    };

    spl_autoload_register($callback);
  }
}
?>
