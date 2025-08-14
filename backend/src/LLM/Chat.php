<?php

declare(strict_types=1);

namespace App\LLM;

use App\Database;
use GuzzleHttp\Client;
use PDO;

class Chat
{
	private Client $http;

	public function __construct()
	{
		$this->http = new Client(['timeout' => 60]);
	}

	public function answerWithRag(int $userId, string $question): string
	{
		$context = $this->retrieveContext($question, 4);
		$prompt = "You are a helpful university student support assistant. Use the context to answer.\n\nContext:\n" . $context . "\n\nQuestion: " . $question . "\nAnswer:";
		return $this->chat($prompt);
	}

	private function retrieveContext(string $query, int $k): string
	{
		$emb = new Embeddings();
		$qvec = $emb->embed($query);
		$pdo = Database::pdo();
		$stmt = $pdo->query('SELECT id, content, embedding FROM kb_chunks');
		$top = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$score = $this->cosineSimilarity($qvec, $row['embedding']);
			$top[] = ['score' => $score, 'content' => $row['content']];
		}
		usort($top, fn($a, $b) => $b['score'] <=> $a['score']);
		$selected = array_slice($top, 0, $k);
		return implode("\n---\n", array_map(fn($r) => $r['content'], $selected));
	}

	private function cosineSimilarity(string $packedA, string $packedB): float
	{
		$a = array_values(unpack('f*', $packedA) ?: []);
		$b = array_values(unpack('f*', $packedB) ?: []);
		$len = min(count($a), count($b));
		if ($len === 0) return 0.0;
		$dot = 0.0; $na = 0.0; $nb = 0.0;
		for ($i = 0; $i < $len; $i++) {
			$dot += $a[$i] * $b[$i];
			$na += $a[$i] * $a[$i];
			$nb += $b[$i] * $b[$i];
		}
		$den = sqrt($na) * sqrt($nb);
		return $den > 0 ? $dot / $den : 0.0;
	}

	private function chat(string $prompt): string
	{
		$provider = $_ENV['PROVIDER'] ?? 'openai';
		if ($provider === 'azure') {
			return $this->chatAzure($prompt);
		}
		return $this->chatOpenAI($prompt);
	}

	private function chatAzure(string $prompt): string
	{
		$endpoint = rtrim($_ENV['AZURE_OPENAI_ENDPOINT'], '/');
		$deployment = $_ENV['AZURE_OPENAI_CHAT_DEPLOYMENT'];
		$res = $this->http->post($endpoint . '/openai/deployments/' . $deployment . '/chat/completions?api-version=' . ($_ENV['AZURE_OPENAI_API_VERSION'] ?? '2024-02-15-preview'), [
			'headers' => [
				'api-key' => $_ENV['AZURE_OPENAI_API_KEY'],
				'content-type' => 'application/json',
			],
			'json' => [
				'messages' => [
					['role' => 'system', 'content' => 'You are a helpful assistant.'],
					['role' => 'user', 'content' => $prompt],
				],
			],
		]);
		$body = json_decode((string)$res->getBody(), true);
		return $body['choices'][0]['message']['content'] ?? '';
	}

	private function chatOpenAI(string $prompt): string
	{
		$res = $this->http->post('https://api.openai.com/v1/chat/completions', [
			'headers' => [
				'Authorization' => 'Bearer ' . $_ENV['OPENAI_API_KEY'],
				'content-type' => 'application/json',
			],
			'json' => [
				'model' => $_ENV['OPENAI_CHAT_MODEL'] ?? 'gpt-4o-mini',
				'messages' => [
					['role' => 'system', 'content' => 'You are a helpful assistant.'],
					['role' => 'user', 'content' => $prompt],
				],
			],
		]);
		$body = json_decode((string)$res->getBody(), true);
		return $body['choices'][0]['message']['content'] ?? '';
	}
}