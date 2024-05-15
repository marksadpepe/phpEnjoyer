<?php
namespace Blog;

use Blog\Router;

$router = new Router();
$router->set_namespace("Blog\\Controllers");
$router->set_middleware_namespace("Blog\\Middlewares");

$router->post("/sign-up", null, "AuthController::sign_up");
$router->post("/sign-in", null, "AuthController::sign_in");
$router->post("/sign-out", null, "AuthController::sign_out");

$router->get("/api/users", ["AuthMiddleware::handle"],  "UserController::get_users");
$router->get("/api/users/{uid}", ["AuthMiddleware::handle"],  "UserController::get_user");
$router->post("/api/users", ["AuthMiddleware::handle"], "UserController::create_user");
$router->delete("/api/users/{uid}", ["AuthMiddleware::handle"], "UserController::delete_user");

return $router;
?>
