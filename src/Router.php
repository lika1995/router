<?php
namespace Lika\Router;

class Router
{
    protected static $routes = [];
    protected static $route = [];
    protected static $controllerNamespace;

    /**
     * @param $regexp
     * @param array $route
     *
     * @return void
     */
    public static function addRoute($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    /**
     *
     * @return array возвращает массив маршрутов
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * @return array
     */
    public static function getRoute()
    {
        return self::$route;
    }

    private static function matchRoute($url)
    {
        foreach (self::getRoutes() as $pattern => $route)
        {
            if(preg_match("~$pattern~", $url, $matches)){
                foreach ($matches as $key => $value) {
                    if(is_string($key)){
                        $route[$key] = $value;
                    }
                }
                if(!isset($route['action'])){
                    $route['action'] = 'index';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * Перенаправляет URL по корректному маршруту
     *
     * @param string $url входящий URL
     * @return void
     */
    public static function dispatch($url)
    {
        if(self::matchRoute($url)){
            $controller = self::$controllerNamespace . self::upperCamelCase(self::$route['controller']);
            if(class_exists($controller)){
                $controllerObj = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if(method_exists($controllerObj, $action)){
                    $controllerObj->$action();
                }else {
                    echo "<b>$controller::$action</b> not found";
                }
            }else {
                echo "{$controller} <h1>not found</h1>";
            }
        }else {
            http_response_code(404);
            include '404.html';
        }
    }

    protected static function upperCamelCase($name)
    {
         return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    protected static function lowerCamelCase($name)
    {
        return lcfirst(self::upperCamelCase($name));
    }

    public static function setControllerNamespace($namespace)
    {
        self::$controllerNamespace = $namespace;
    }
}
