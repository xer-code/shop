<?php

namespace App\Core;

/**
 * Router — Maps URL patterns to Controller@method
 * Supports GET/POST, route parameters, middleware groups
 */
class Router
{
    private array $routes = [];
    private array $middlewareGroups = [];
    private string $currentPrefix = '';
    private array $currentMiddleware = [];

    /**
     * Register a GET route
     */
    public function get(string $pattern, string $action): self
    {
        return $this->addRoute('GET', $pattern, $action);
    }

    /**
     * Register a POST route
     */
    public function post(string $pattern, string $action): self
    {
        return $this->addRoute('POST', $pattern, $action);
    }

    /**
     * Register route group with prefix and middleware
     */
    public function group(array $options, callable $callback): void
    {
        $previousPrefix = $this->currentPrefix;
        $previousMiddleware = $this->currentMiddleware;

        if (isset($options['prefix'])) {
            $this->currentPrefix = $previousPrefix . '/' . trim($options['prefix'], '/');
        }
        if (isset($options['middleware'])) {
            $mw = is_array($options['middleware']) ? $options['middleware'] : [$options['middleware']];
            $this->currentMiddleware = array_merge($previousMiddleware, $mw);
        }

        $callback($this);

        $this->currentPrefix = $previousPrefix;
        $this->currentMiddleware = $previousMiddleware;
    }

    /**
     * Add a route to the route table
     */
    private function addRoute(string $method, string $pattern, string $action): self
    {
        $fullPattern = $this->currentPrefix . '/' . trim($pattern, '/');
        $fullPattern = '/' . trim($fullPattern, '/');
        if ($fullPattern === '/') {
            $fullPattern = '';
        }

        $this->routes[] = [
            'method'     => $method,
            'pattern'    => $fullPattern,
            'action'     => $action,
            'middleware'  => $this->currentMiddleware,
        ];

        return $this;
    }

    /**
     * Dispatch the request to the appropriate controller
     */
    public function dispatch(string $url, string $method): void
    {
        $url = '/' . trim($url, '/');
        if ($url === '/') {
            $url = '';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $params = $this->matchRoute($route['pattern'], $url);
            if ($params !== false) {
                // Run middleware
                foreach ($route['middleware'] as $middleware) {
                    $this->runMiddleware($middleware);
                }

                // Parse action: Controller@method
                list($controllerName, $methodName) = explode('@', $route['action']);
                $controllerClass = "App\\Controllers\\{$controllerName}";

                if (!class_exists($controllerClass)) {
                    $this->sendError(404, "Controller {$controllerName} not found");
                    return;
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $methodName)) {
                    $this->sendError(404, "Method {$methodName} not found in {$controllerName}");
                    return;
                }

                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }

        // No route matched
        $this->sendError(404, "Page not found");
    }

    /**
     * Match a route pattern against a URL, returning extracted params or false
     */
    private function matchRoute(string $pattern, string $url): array|false
    {
        // Exact match (no params)
        if ($pattern === $url) {
            return [];
        }

        // Convert pattern to regex: {param} → ([^/]+)
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $url, $matches)) {
            array_shift($matches); // Remove full match
            return $matches;
        }

        return false;
    }

    /**
     * Run a middleware check
     */
    private function runMiddleware(string $middleware): void
    {
        switch ($middleware) {
            case 'auth':
                if (!Auth::check()) {
                    Session::flash('error', 'Please sign in to continue.');
                    header('Location: ' . url('/login'));
                    exit;
                }
                break;
            case 'admin':
                if (!Auth::check() || !Auth::isAdmin()) {
                    Session::flash('error', 'Unauthorized access.');
                    header('Location: ' . url('/admin/login'));
                    exit;
                }
                break;
            case 'guest':
                if (Auth::check()) {
                    header('Location: ' . url('/'));
                    exit;
                }
                break;
        }
    }

    /**
     * Send an error response
     */
    private function sendError(int $code, string $message): void
    {
        http_response_code($code);
        if ($code === 404) {
            $view = new View();
            $view->renderError($code, $message);
        } else {
            echo $message;
        }
    }
}
