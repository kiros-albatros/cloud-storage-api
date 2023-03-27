<?php

class Core
{
    protected string $currentController = 'MainController';
    protected string $currentMethod = 'main';
    protected array $params = [];
    protected bool $isRouteFound = false;

    public function __construct()
    {
        $uri = '';
        if (isset($_GET['route'])) {
            $uri = rtrim($_GET['route'], '/');
        }
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $controllerAndAction = $this->route($uri, $method);

        if (file_exists('src/Controllers/' . $controllerAndAction['className'] . '.php')) {
            $this->currentController = $controllerAndAction['className'];
        }

        require_once 'src/Controllers/' . $this->currentController . '.php';

        if (method_exists($this->currentController, $controllerAndAction['methodName'])) {
            $this->currentMethod = $controllerAndAction['methodName'];
        }

        $this->params = $controllerAndAction['arg'];

        call_user_func_array([new $this->currentController, $this->currentMethod], $this->params);
    }

    public function route($uri, $method)
    {
        foreach (ROUTES as $urlItem => $controllerAndAction) {
            $pattern = '~^' . $urlItem . '$~';
            $pattern = str_replace('{id}', '([0-9]{1,})', $pattern);
            $pattern = str_replace('{email}', '(.{1,})', $pattern);
            $pattern = str_replace('{user_id}', '([0-9]{1,})', $pattern);
            preg_match($pattern, $uri, $matches);
            if (!empty($matches)) {
                foreach (ROUTES[$urlItem] as $method_item => $action) {
                    if ($method_item == $method) {
                        $this->isRouteFound = true;
                        $arr = explode("::", $action);
                        $className = $arr[0];
                        $methodName = substr($arr[1], 0, -2);
                        if (count($matches) > 1) {
                            array_shift($matches);
                            $arg = array_values($matches);
                            $data = ['className' => $className, 'methodName' => $methodName, 'arg' => $arg];
                        } else {
                            $data = ['className' => $className, 'methodName' => $methodName, 'arg' => []];
                        }
                        return $data;
                        break;
                    }
                }
            }
        }
        if ($this->isRouteFound === false) {
            return $data = ['className' => 'MainController', 'methodName' => 'notFound', 'arg' => []];
        }
    }
}