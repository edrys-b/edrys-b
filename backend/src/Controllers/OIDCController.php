<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Utils\Response;
use App\Utils\Auth;
use Jumbojett\OpenIDConnectClient;
use PDO;

class OIDCController
{
	public function login(): void
	{
		$oidc = $this->client();
		$oidc->addScope('openid email profile');
		$oidc->authenticate();
	}

	public function callback(): void
	{
		$oidc = $this->client();
		$oidc->addScope('openid email profile');
		$oidc->authenticate();
		$email = $oidc->requestUserInfo('email');
		$name = $oidc->requestUserInfo('name') ?: ($oidc->requestUserInfo('preferred_username') ?? 'Student');

		$pdo = Database::pdo();
		$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
		$stmt->execute([$email]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$user) {
			$insert = $pdo->prepare('INSERT INTO users (email, password_hash, name, role, provider) VALUES (?, NULL, ?, "student", "azuread")');
			$insert->execute([$email, $name]);
			$userId = (int)$pdo->lastInsertId();
		} else {
			$userId = (int)$user['id'];
		}
		$token = Auth::issueToken($userId);
		// Redirect back to frontend with token fragment
		$origin = $_ENV['FRONTEND_ORIGIN'] ?? 'http://localhost';
		header('Location: ' . rtrim($origin, '/') . '/auth/callback#token=' . urlencode($token));
	}

	private function client(): OpenIDConnectClient
	{
		$tenant = $_ENV['AZURE_AD_TENANT_ID'];
		$issuer = 'https://login.microsoftonline.com/' . $tenant . '/v2.0';
		$client = new OpenIDConnectClient(
			$issuer,
			$_ENV['AZURE_AD_CLIENT_ID'],
			$_ENV['AZURE_AD_CLIENT_SECRET']
		);
		$client->setRedirectURL($_ENV['AZURE_AD_REDIRECT_URI']);
		return $client;
	}
}