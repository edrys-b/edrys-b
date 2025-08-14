<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Utils\Auth;
use App\Utils\Response;
use PDO;

class ResourceController
{
	public function uploadResource(): void
	{
		Auth::requireAdmin();
		if (!isset($_FILES['file'])) {
			Response::json(['error' => true, 'message' => 'No file'], 400);
			return;
		}
		$uploads = dirname(__DIR__, 2) . '/storage/uploads/resources';
		if (!is_dir($uploads)) {
			@mkdir($uploads, 0775, true);
		}
		$filename = time() . '-' . preg_replace('#[^a-zA-Z0-9._-]#', '_', $_FILES['file']['name']);
		$dest = $uploads . '/' . $filename;
		move_uploaded_file($_FILES['file']['tmp_name'], $dest);
		$title = $_POST['title'] ?? $_FILES['file']['name'];
		$desc = $_POST['description'] ?? null;
		$pdo = Database::pdo();
		$ins = $pdo->prepare('INSERT INTO resources (title, description, path, uploaded_by) VALUES (?, ?, ?, ?)');
		$ins->execute([$title, $desc, $dest, Auth::requireUser()['id']]);
		Response::json(['ok' => true]);
	}

	public function listResources(): void
	{
		Auth::requireUser();
		$pdo = Database::pdo();
		$list = $pdo->query('SELECT id, title, description, created_at FROM resources ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
		Response::json(['resources' => $list]);
	}

	public function downloadResource(string $id): void
	{
		Auth::requireUser();
		$pdo = Database::pdo();
		$stmt = $pdo->prepare('SELECT * FROM resources WHERE id = ?');
		$stmt->execute([(int)$id]);
		$file = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$file || !is_file($file['path'])) {
			Response::json(['error' => true, 'message' => 'Not found'], 404);
			return;
		}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($file['path']) . '"');
		header('Content-Length: ' . filesize($file['path']));
		readfile($file['path']);
	}
}