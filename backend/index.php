<?php
require_once __DIR__ . "/bootstrap.php";
require_once __DIR__ . "/config.php";
$router = require __DIR__ . "/routes.php";

use Blog\Database;
use Blog\Request;
use Blog\Response;

$db = new Database(
  $DB_HOST,
  $DB_USERNAME,
  $DB_PASSWORD,
  $DB_NAME,
  $DB_PORT
);

$redis = new Redis();
$redis->connect($REDIS_HOST, $REDIS_PORT);

$request = new Request();
$response = $router->route($request);
if ($response) {
  $response->send();
}

$redis->close();
?>
