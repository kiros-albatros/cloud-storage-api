<?php

class Core
{
    protected string $currentController = 'MainController';
    protected string $currentMethod = 'main';
    protected $params = '';
    protected bool $isRouteFound = false;

    public function __construct()
    {
        $uri = $_GET['route'] ?? '';
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $controllerAndAction = $this->route($uri, $method);
      //  var_dump(['$controllerAndAction' => $controllerAndAction]);

        if (file_exists('src/Controllers/' . $controllerAndAction['className'] . '.php')) {
         //   echo "exists \n";
            $this->currentController = $controllerAndAction['className'];
        }

        require_once 'src/Controllers/' . $this->currentController . '.php';

        if (method_exists($this->currentController, $controllerAndAction['methodName'])) {
            $this->currentMethod = $controllerAndAction['methodName'];
        }

        $this->params = $controllerAndAction['arg'];

        $controller = new $this->currentController();
        $actionName = $this->currentMethod;
        $controller->$actionName($this->params);

        //  call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function route($uri, $method)
    {
        foreach (ROUTES as $urlItem => $controllerAndAction) {
            $pattern = '~^' . $urlItem . '$~';
            $pattern = str_replace('{id}', '([0-9]{1,})', $pattern);
            preg_match($pattern, $uri, $matches);
            if (!empty($matches)) {
                foreach (ROUTES[$urlItem] as $method_item => $action) {
                    if ($method_item == $method) {
                        $this->isRouteFound = true;
                        $arr = explode("::", $action);
                        $className = $arr[0];
                        $methodName = substr($arr[1], 0, -2);
                        if (!empty ($matches[1])) {
                            $data = ['className' => $className, 'methodName' => $methodName, 'arg' => $matches[1]];
                        } else {
                            $data = ['className' => $className, 'methodName' => $methodName, 'arg' => ''];
                        }
                        return $data;
                        break;
                    }
                }
            }
        }
        if ($this->isRouteFound === false) {
            return $data = ['className' => 'MainController', 'methodName' => 'notFound', 'arg' => ''];
        }
    }
}