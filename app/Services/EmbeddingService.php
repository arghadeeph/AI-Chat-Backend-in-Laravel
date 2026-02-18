<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;


class EmbeddingService
{
    public function embed(string $text): array
    {
        $text = mb_strtolower(trim($text));

        $response = Http::timeout(60)->post('http://localhost:11434/api/embeddings', [
            'model' => 'nomic-embed-text',
            'prompt' => $text,
        ]);

        if (! $response->successful()) {
            throw new \Exception('Embedding API failed');
        }

        $data = $response->json();

        // ✅ HARD GUARD
        if (! isset($data['embedding']) || ! is_array($data['embedding'])) {
            throw new \Exception('Invalid embedding response');
        }

        return $data['embedding'];
    }
}

