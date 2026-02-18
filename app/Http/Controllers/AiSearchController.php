<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Services\AiService;
use App\Services\RagService;

class AiSearchController extends Controller
{
    public function search(Request $request, RagService $rag){
        
        $request->validate([
            'q'=>'required|string|max:2000',
        ]);

        $question = $request->input('q');

        //Build Rag Prompt
        $prompt = $rag->buildPrompt($question);

        $history = collect(session('chat_history', []))
            ->take(-6) // last 3 turns (user + assistant)
            ->values()
            ->all();

        ///($prompt);

        $messages = array_merge(
            [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant. Answer using the provided context.'
                ],
            ],
            $history,
            [
                [
                    'role' => 'user',
                    'content' => $prompt // RAG prompt with context
                ]
            ]
        );

        // Ask local LLM
        $response = Http::timeout(120)->post('http://localhost:11434/api/chat', [
            'model' => 'llama3',
            'messages' => $messages,
            'stream' => false
        ]);

        $data = $response->json();

        $answer = $data['message']['content'] ?? 'No response';

          session()->push('chat_history', [
            'role' => 'user',
            'content' => $question,
        ]);

        session()->push('chat_history', [
            'role' => 'assistant',
            'content' => $answer,
        ]);

        return response()->json([
            'answer' => $answer,
        ]);
    }
}
