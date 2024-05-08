<?php
namespace Blog;

use Blog\Router;

$router = new Router();
$router->set_namespace("Blog\\Controllers");
$router->set_middleware_namespace("Blog\\Middlewares");

//$router->post("/sign-up", "AuthController::sign_up");
$router->post("/sign-in", null, "AuthController::sign_in");

$router->get("/api/users", ["AuthMiddleware::handle"],  "UserController::get_users");

return $router;
?>
