<?php

declare(strict_types=1);

namespace App\Utils;

use App\Database;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use PDO;

class Auth
{
	public static function issueToken(int $userId): string
	{
		$now = new DateTimeImmutable();
		$exp = $now->modify('+' . ((int)($_ENV['JWT_EXPIRES_HOURS'] ?? 24)) . ' hours')->getTimestamp();
		$jwtId = bin2hex(random_bytes(16));

		$payload = [
			'iss' => 'cs-assistant',
			'sub' => (string)$userId,
			'iat' => $now->getTimestamp(),
			'exp' => $exp,
			'jti' => $jwtId,
		];
		$token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

		$pdo = Database::pdo();
		$insert = $pdo->prepare('INSERT INTO sessions (user_id, jwt_id, expires_at) VALUES (?, ?, FROM_UNIXTIME(?))');
		$insert->execute([$userId, $jwtId, $exp]);
		return $token;
	}

	public static function userFromAuthHeader(): ?array
	{
		$auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
		$token = null;
		if (preg_match('#^Bearer\s+(.+)$#i', $auth, $m)) {
			$token = $m[1];
		} elseif (!empty($_GET['token'])) {
			$token = (string)$_GET['token'];
		}
		if (!$token) {
			return null;
		}
		try {
			$decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
			$pdo = Database::pdo();
			$stmt = $pdo->prepare('SELECT u.* FROM sessions s JOIN users u ON u.id = s.user_id WHERE s.jwt_id = ? AND s.expires_at > NOW()');
			$stmt->execute([$decoded->jti]);
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			return $user ?: null;
		} catch (\Throwable $e) {
			return null;
		}
	}

	public static function requireUser(): array
	{
		$user = self::userFromAuthHeader();
		if (!$user) {
			Response::json(['error' => true, 'message' => 'Unauthorized'], 401);
			exit;
		}
		return $user;
	}

	public static function requireAdmin(): array
	{
		$user = self::requireUser();
		if (($user['role'] ?? 'student') !== 'admin') {
			Response::json(['error' => true, 'message' => 'Forbidden'], 403);
			exit;
		}
		return $user;
	}
}