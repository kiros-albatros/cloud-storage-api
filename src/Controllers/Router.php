<?php

class Router
{
    static bool $isRouteFound = false;

    static array $urlList = [
        ''=> ['GET' => 'MainController::main()'],
        'funds' => ['GET' => 'Funds::list()', 'POST' => 'Funds::add()'],
        'user/{id}' => ['GET' => 'User::show()', 'PUT' => 'User::update()', 'DELETE' => 'User::delete()'],
        'user/' => ['GET' => 'User::list()', 'POST' => 'User::add()'],
    ];

    static function pathNotFound()
    {
        return $data = ['className' => 'MainController', 'methodName' => 'notFound', 'arg'=> ''];
    }

    public function route($uri, $method)
    {
        foreach (self::$urlList as $urlItem => $controllerAndAction) {
          //  var_dump(['urlItem'=> $urlItem]);
            $pattern = '~^'. $urlItem .'$~';
            $pattern = str_replace('{id}', '([0-9])', $pattern);
          //  var_dump(['pattern'=> $pattern]);
          //  var_dump(['uri'=> $uri]);
            preg_match($pattern, $uri, $matches);
            if (!empty($matches)) {
            //    var_dump(['matches'=> $matches]);
                foreach (self::$urlList[$urlItem] as $method_item => $action) {
                    if ($method_item == $method) {
                        self::$isRouteFound = true;
                        $arr = explode("::", $action);
                        $className = $arr[0];
                        $methodName = substr($arr[1], 0, -2);
                        if (!empty ($matches[1])) {
                            $data = ['className' => $className, 'methodName' => $methodName, 'arg'=> $matches[1]];
                        } else {
                            $data = ['className' => $className, 'methodName' => $methodName, 'arg'=> ''];
                        }
                     //   var_dump(['data' => $data]);
                        return $data;
                        break;
                    }
                }
            }
        }
        if (self::$isRouteFound === false) {
            return $this::pathNotFound();
        }
    }
}
