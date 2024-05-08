<?php
use Blog\ClassLoader;

require_once __DIR__ . "/ClassLoader.php";

ClassLoader::init([
  "Blog\\" => __DIR__
]);
?>
