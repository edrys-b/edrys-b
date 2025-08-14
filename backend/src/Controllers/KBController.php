<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Utils\Auth;
use App\Utils\Response;
use App\LLM\Embeddings;
use PDO;

class KBController
{
	public function uploadDocument(): void
	{
		Auth::requireAdmin();
		if (!isset($_FILES['file'])) {
			Response::json(['error' => true, 'message' => 'No file'], 400);
			return;
		}
		$maxMb = (int)($_ENV['UPLOAD_MAX_MB'] ?? 15);
		if ($_FILES['file']['size'] > $maxMb * 1024 * 1024) {
			Response::json(['error' => true, 'message' => 'File too large'], 413);
			return;
		}
		$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		if (!in_array($ext, ['txt','md'])) {
			Response::json(['error' => true, 'message' => 'Unsupported type. Use .txt or .md'], 415);
			return;
		}
		$uploads = dirname(__DIR__, 2) . '/storage/uploads/kb';
		if (!is_dir($uploads)) {
			@mkdir($uploads, 0775, true);
		}
		$filename = time() . '-' . preg_replace('#[^a-zA-Z0-9._-]#', '_', $_FILES['file']['name']);
		$dest = $uploads . '/' . $filename;
		move_uploaded_file($_FILES['file']['tmp_name'], $dest);

		$title = $_POST['title'] ?? $_FILES['file']['name'];
		$pdo = Database::pdo();
		$ins = $pdo->prepare('INSERT INTO kb_documents (title, source, source_url, path, created_by) VALUES (?, "upload", NULL, ?, ?)');
		$ins->execute([$title, $dest, Auth::requireUser()['id']]);
		$docId = (int)$pdo->lastInsertId();

		$text = file_get_contents($dest);
		$this->chunkAndEmbed($docId, $text);
		Response::json(['ok' => true, 'id' => $docId]);
	}

	public function ingestUrl(): void
	{
		Auth::requireAdmin();
		$input = $_POST ?: (json_decode(file_get_contents('php://input'), true) ?? []);
		$url = trim((string)($input['url'] ?? ''));
		$title = (string)($input['title'] ?? $url);
		if (!$url) {
			Response::json(['error' => true, 'message' => 'Missing url'], 422);
			return;
		}
		$html = @file_get_contents($url);
		if (!$html) {
			Response::json(['error' => true, 'message' => 'Failed to fetch url'], 400);
			return;
		}
		$text = strip_tags($html);
		$pdo = Database::pdo();
		$ins = $pdo->prepare('INSERT INTO kb_documents (title, source, source_url, path, created_by) VALUES (?, "url", ?, NULL, ?)');
		$ins->execute([$title, $url, Auth::requireUser()['id']]);
		$docId = (int)$pdo->lastInsertId();
		$this->chunkAndEmbed($docId, $text);
		Response::json(['ok' => true, 'id' => $docId]);
	}

	public function listDocuments(): void
	{
		Auth::requireAdmin();
		$pdo = Database::pdo();
		$docs = $pdo->query('SELECT * FROM kb_documents ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
		Response::json(['documents' => $docs]);
	}

	public function deleteDocument(string $id): void
	{
		Auth::requireAdmin();
		$pdo = Database::pdo();
		$del = $pdo->prepare('DELETE FROM kb_documents WHERE id = ?');
		$del->execute([(int)$id]);
		Response::json(['ok' => true]);
	}

	private function chunkAndEmbed(int $docId, string $text): void
	{
		$chunks = $this->chunkText($text, 800, 150);
		$emb = new Embeddings();
		$pdo = Database::pdo();
		$ins = $pdo->prepare('INSERT INTO kb_chunks (document_id, chunk_index, content, embedding) VALUES (?, ?, ?, ?)');
		foreach ($chunks as $i => $chunk) {
			$vector = $emb->embed($chunk);
			$ins->execute([$docId, $i, $chunk, $vector]);
		}
	}

	private function chunkText(string $text, int $size, int $overlap): array
	{
		$text = trim(preg_replace('#\s+#', ' ', $text));
		$chunks = [];
		$len = strlen($text);
		$start = 0;
		while ($start < $len) {
			$end = min($len, $start + $size);
			$segment = substr($text, $start, $end - $start);
			$chunks[] = $segment;
			$start = $end - $overlap;
			if ($start < 0) $start = 0;
		}
		return $chunks;
	}
}