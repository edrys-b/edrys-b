<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\LLM\Chat as ChatLLM;
use App\Utils\Auth;
use App\Utils\Response;
use PDO;

class ChatController
{
	public function chat(): void
	{
		$user = Auth::requireUser();
		$input = json_decode(file_get_contents('php://input'), true) ?? [];
		$message = trim((string)($input['message'] ?? ''));
		$chatId = isset($input['chatId']) ? (int)$input['chatId'] : 0;
		if (!$message) {
			Response::json(['error' => true, 'message' => 'Message required'], 422);
			return;
		}
		$pdo = Database::pdo();
		if ($chatId <= 0) {
			$ins = $pdo->prepare('INSERT INTO chats (user_id, title) VALUES (?, ?)');
			$ins->execute([(int)$user['id'], substr($message, 0, 60)]);
			$chatId = (int)$pdo->lastInsertId();
		}
		$insMsg = $pdo->prepare('INSERT INTO messages (chat_id, role, content) VALUES (?, ?, ?)');
		$insMsg->execute([$chatId, 'user', $message]);

		$llm = new ChatLLM();
		$answer = $llm->answerWithRag((int)$user['id'], $message);
		$insMsg->execute([$chatId, 'assistant', $answer]);
		Response::json(['chatId' => $chatId, 'answer' => $answer]);
	}

	public function history(): void
	{
		$user = Auth::requireUser();
		$pdo = Database::pdo();
		$chats = $pdo->prepare('SELECT id, title, created_at FROM chats WHERE user_id = ? ORDER BY created_at DESC');
		$chats->execute([(int)$user['id']]);
		$list = $chats->fetchAll(PDO::FETCH_ASSOC);
		Response::json(['chats' => $list]);
	}
}