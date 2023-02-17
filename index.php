<?php

require_once("./autoload.php");

$uri = $_GET['route'] ?? '';
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

// $controller = new MainController();
// $controller->main();

$route = new Router();
$controllerAndAction = $route->route($uri, $method);

$controllerName = $controllerAndAction['className'];
$actionName = $controllerAndAction['methodName'];
$argument = $controllerAndAction['arg'];

$controller = new $controllerName();
$controller->$actionName($argument);