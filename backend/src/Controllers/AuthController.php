<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Utils\Auth;
use App\Utils\Response;
use PDO;

class AuthController
{
	public function register(): void
	{
		$input = json_decode(file_get_contents('php://input'), true) ?? [];
		$email = trim((string)($input['email'] ?? ''));
		$name = trim((string)($input['name'] ?? ''));
		$password = (string)($input['password'] ?? '');
		if (!$email || !$name || !$password) {
			Response::json(['error' => true, 'message' => 'Missing fields'], 422);
			return;
		}
		$pdo = Database::pdo();
		$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
		$stmt->execute([$email]);
		if ($stmt->fetch()) {
			Response::json(['error' => true, 'message' => 'Email already exists'], 409);
			return;
		}
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$insert = $pdo->prepare('INSERT INTO users (email, password_hash, name, role, provider) VALUES (?, ?, ?, "student", "local")');
		$insert->execute([$email, $hash, $name]);
		$userId = (int)$pdo->lastInsertId();
		$token = Auth::issueToken($userId);
		Response::json(['token' => $token]);
	}

	public function login(): void
	{
		$input = json_decode(file_get_contents('php://input'), true) ?? [];
		$email = trim((string)($input['email'] ?? ''));
		$password = (string)($input['password'] ?? '');
		$pdo = Database::pdo();
		$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
		$stmt->execute([$email]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$user || !($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
			Response::json(['error' => true, 'message' => 'Invalid credentials'], 401);
			return;
		}
		$token = Auth::issueToken((int)$user['id']);
		Response::json(['token' => $token]);
	}

	public function me(): void
	{
		$user = Auth::requireUser();
		unset($user['password_hash']);
		Response::json(['user' => $user]);
	}

	public function logout(): void
	{
		// Best-effort invalidate: delete session by jti
		$auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
		if (preg_match('#^Bearer\s+(.+)$#i', $auth, $m)) {
			try {
				$parts = explode('.', $m[1]);
				$payload = json_decode(base64_decode(strtr($parts[1] ?? '', '-_', '+/')), true) ?? [];
				$jti = $payload['jti'] ?? '';
				if ($jti) {
					$pdo = Database::pdo();
					$del = $pdo->prepare('DELETE FROM sessions WHERE jwt_id = ?');
					$del->execute([$jti]);
				}
			} catch (\Throwable $e) {
				// ignore
			}
		}
		Response::json(['ok' => true]);
	}
}