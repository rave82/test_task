<?php

namespace Components;

class Router
{
    private $routes;
    
    public function __construct()
    {
        $this->routes = require(CURRENT_WORK_DIR . '/config/routers.php');
    }
    
    private function getURI()
    {
        $uri = '';
        
        if(isset($_SERVER['REQUEST_URI']))
        {
            $uri = trim(array_shift(explode('?', trim($_SERVER['REQUEST_URI'], '/'))), '/');
        }
        ($uri == '') ? $uri = 'index' : '';
        
        return $uri;
    }
    
    public function run()
    {
        $uri = $this->getURI();
        $isJson = (int)getRequest('json');
        $result = '';
        
        foreach($this->routes as $key=>$value)
        {
            if(preg_match("/".$key."/", $uri))
            {
                $segments = explode('/', $value);
                
                $controllerName = ucfirst(array_shift($segments)).'Controller';
                $actionName = 'action'.ucfirst(array_shift($segments));
                
                $controller = eval("return new Controllers\\".$controllerName."();");
                $result = call_user_func_array([$controller, $actionName], $segments);
                
                if($result)
                {
                    break;
                }
            }
        }
        
        if($result && !$isJson)
        {
            $result = (new \Views\View())->render($result);
        }
        
        return $result;
    }
}
