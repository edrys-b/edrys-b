<?php

declare(strict_types=1);

namespace App\LLM;

use GuzzleHttp\Client;

class Embeddings
{
	private Client $http;

	public function __construct()
	{
		$this->http = new Client(['timeout' => 30]);
	}

	public function embed(string $text): string
	{
		$provider = $_ENV['PROVIDER'] ?? 'openai';
		if ($provider === 'azure') {
			return $this->embedAzure($text);
		}
		return $this->embedOpenAI($text);
	}

	private function embedAzure(string $text): string
	{
		$endpoint = rtrim($_ENV['AZURE_OPENAI_ENDPOINT'], '/');
		$deployment = $_ENV['AZURE_OPENAI_EMBED_DEPLOYMENT'];
		$res = $this->http->post($endpoint . '/openai/deployments/' . $deployment . '/embeddings?api-version=' . ($_ENV['AZURE_OPENAI_API_VERSION'] ?? '2024-02-15-preview'), [
			'headers' => [
				'api-key' => $_ENV['AZURE_OPENAI_API_KEY'],
				'content-type' => 'application/json',
			],
			'json' => [
				'input' => $text,
			],
		]);
		$body = json_decode((string)$res->getBody(), true);
		$vector = $body['data'][0]['embedding'] ?? [];
		return pack('f*', ...$vector);
	}

	private function embedOpenAI(string $text): string
	{
		$res = $this->http->post('https://api.openai.com/v1/embeddings', [
			'headers' => [
				'Authorization' => 'Bearer ' . $_ENV['OPENAI_API_KEY'],
				'content-type' => 'application/json',
			],
			'json' => [
				'model' => $_ENV['OPENAI_EMBED_MODEL'] ?? 'text-embedding-3-large',
				'input' => $text,
			],
		]);
		$body = json_decode((string)$res->getBody(), true);
		$vector = $body['data'][0]['embedding'] ?? [];
		return pack('f*', ...$vector);
	}
}