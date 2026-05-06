<?php

class Router {
    private $routes = [];
    
    public function add($method, $path, $handler) {
        $this->routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri, '/');
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            
            $pattern = '#^' . preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^/]+)', $route['path']) . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func_array($route['handler'], $params);
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
    }
}
