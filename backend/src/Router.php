<?php

declare(strict_types=1);

namespace App;

use App\Utils\Response;

class Router
{
	private array $routes = [];

	public function registerRoutes(): void
	{
		$this->routes = [
			['POST', '/auth/register', [Controllers\AuthController::class, 'register']],
			['POST', '/auth/login', [Controllers\AuthController::class, 'login']],
			['GET', '/auth/me', [Controllers\AuthController::class, 'me']],
			['GET', '/auth/logout', [Controllers\AuthController::class, 'logout']],

			['GET', '/auth/oidc/login', [Controllers\OIDCController::class, 'login']],
			['GET', '/auth/oidc/callback', [Controllers\OIDCController::class, 'callback']],

			['GET', '/admin/metrics', [Controllers\AdminController::class, 'metrics']],

			['POST', '/kb/upload', [Controllers\KBController::class, 'uploadDocument']],
			['POST', '/kb/url', [Controllers\KBController::class, 'ingestUrl']],
			['GET', '/kb/list', [Controllers\KBController::class, 'listDocuments']],
			['DELETE', '/kb/:id', [Controllers\KBController::class, 'deleteDocument']],

			['POST', '/resources/upload', [Controllers\ResourceController::class, 'uploadResource']],
			['GET', '/resources/list', [Controllers\ResourceController::class, 'listResources']],
			['GET', '/resources/:id/download', [Controllers\ResourceController::class, 'downloadResource']],

			['POST', '/chat', [Controllers\ChatController::class, 'chat']],
			['GET', '/chats/history', [Controllers\ChatController::class, 'history']],
		];
	}

	public function dispatch(): void
	{
		$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
		$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

		$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
		if ($base && str_starts_with($uri, $base)) {
			$uri = substr($uri, strlen($base));
		}

		foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
			$params = [];
			if ($method === $routeMethod && $this->match($routePath, $uri, $params)) {
				return $this->invoke($handler, $params);
			}
		}

		Response::json(['error' => true, 'message' => 'Not Found'], 404);
	}

	private function match(string $route, string $uri, array &$params): bool
	{
		$pattern = preg_replace('#:[a-zA-Z_][a-zA-Z0-9_]*#', '([\\w-]+)', $route);
		$pattern = '#^' . $pattern . '$#';
		if (preg_match($pattern, $uri, $matches)) {
			array_shift($matches);
			$params = $matches;
			return true;
		}
		return false;
	}

	private function invoke(array $handler, array $params): void
	{
		[$class, $method] = $handler;
		$instance = new $class();
		$instance->$method(...$params);
	}
}