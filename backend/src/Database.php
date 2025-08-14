<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database
{
	private static ?PDO $pdo = null;

	public static function pdo(): PDO
	{
		if (self::$pdo === null) {
			$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
				$_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME']
			);
			self::$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			]);
		}
		return self::$pdo;
	}
}