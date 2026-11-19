<?php
/**
 * Minimal but solid front-controller router.
 *
 * Supports:
 *   $r->get('/about', [PageController::class, 'about']);
 *   $r->get('/projects/{slug}', [ProjectController::class, 'show']);
 *   $r->post('/contact', [ContactController::class, 'submit']);
 *
 * Placeholders are captured and passed as positional args.
 */
class Router
{
    /** @var array<string, array<int, array{pattern:string, handler:callable|array, params:string[]}>> */
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    public function get(string $path, $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, $handler): void
    {
        $params = [];
        $pattern = preg_replace_callback(
            '~\{([a-zA-Z_][a-zA-Z0-9_]*)\}~',
            function ($m) use (&$params) {
                $params[] = $m[1];
                return '([^/]+)';
            },
            $path
        );
        $pattern = '~^' . $pattern . '$~';
        $this->routes[$method][] = compact('pattern', 'handler', 'params');
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'HEAD') $method = 'GET';

        // Strip query string, decode, strip base path
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $uri = rawurldecode($uri);

        $base = rtrim(SITE_URL, '/');
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        if ($uri === '' || $uri === false) $uri = '/';
        // Remove trailing slash (except root)
        if (strlen($uri) > 1) $uri = rtrim($uri, '/');

        $list = $this->routes[$method] ?? [];
        foreach ($list as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                $this->callHandler($route['handler'], $matches);
                return;
            }
        }

        // Not found
        http_response_code(404);
        view('pages/error_404', ['route' => $uri]);
    }

    private function callHandler($handler, array $args): void
    {
        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            $instance = new $class();
            $instance->$method(...$args);
            return;
        }
        if (is_callable($handler)) {
            $handler(...$args);
            return;
        }
        http_response_code(500);
        die('Invalid route handler');
    }
}
