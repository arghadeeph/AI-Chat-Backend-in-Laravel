<?php

namespace App\Services;

use App\Models\Knowledge;


class RagService
{
    public function __construct(
        protected EmbeddingService $embeddingService
    ) {}

    public function buildPrompt(string $question): string
    {
        //Embed Question
        $queryEmbedding = $this->embeddingService->embed($question);

        // $debug = Knowledge::whereNotNull('embedding')->get()->map(function ($item) use ($queryEmbedding) {
        //     return [
        //         'content' => $item->content,
        //         'score'   => cosineSimilarity($queryEmbedding, $item->embedding),
        //     ];
        // });

        // dd($debug->sortByDesc('score')->values());

        //Similer Search
        $matches = Knowledge::whereNotNull('embedding')->get()
            ->map(function ($item) use ($queryEmbedding) {

                if (count($item->embedding) !== count($queryEmbedding)) {
                    return null;
                }

                return [
                    'content' => $item->content,
                    'score'   => cosineSimilarity($queryEmbedding, $item->embedding),
                ];
            })
            ->filter()
            ->filter(fn ($item) => $item['score'] > 0.3)
            ->sortByDesc('score')
            ->take(3)
            ->values();
                
        //Build Context     
        $context = $matches->pluck('content')->implode("\n---\n");
        
        //Final RAG prompt
        return <<<PROMPT
            Answer using the provided context. 
            If the context is insufficient, say so briefly.

            Context:
            $context

            Question:
            $question
            PROMPT;
    }
}