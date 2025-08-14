<?php

declare(strict_types=1);

use App\Router;
use App\Utils\Response;

require __DIR__ . '/../vendor/autoload.php';

// Load env
$root = dirname(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable($root . '/config');
$dotenv->safeLoad();

// CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigin = $_ENV['FRONTEND_ORIGIN'] ?? '';
if ($origin && $allowedOrigin && stripos($origin, $allowedOrigin) === 0) {
	header('Access-Control-Allow-Origin: ' . $origin);
	header('Vary: Origin');
}
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	exit;
}

try {
	$router = new Router();
	$router->registerRoutes();
	$router->dispatch();
} catch (Throwable $e) {
	Response::json([
		'error' => true,
		'message' => $_ENV['APP_DEBUG'] === 'true' ? $e->getMessage() : 'Server Error',
	], 500);
}