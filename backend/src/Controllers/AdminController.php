<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Utils\Auth;
use App\Utils\Response;

class AdminController
{
	public function metrics(): void
	{
		Auth::requireAdmin();
		$pdo = Database::pdo();
		$users = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
		$chats = (int)$pdo->query('SELECT COUNT(*) FROM chats')->fetchColumn();
		$msgs = (int)$pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
		$docs = (int)$pdo->query('SELECT COUNT(*) FROM kb_documents')->fetchColumn();
		$resources = (int)$pdo->query('SELECT COUNT(*) FROM resources')->fetchColumn();
		Response::json(compact('users', 'chats', 'msgs', 'docs', 'resources'));
	}
}