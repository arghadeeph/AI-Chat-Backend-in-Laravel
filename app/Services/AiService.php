<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;


class AiService
{
    public function askAi(string $query)
    {
        
        $response = Http::timeout(120)->post(
            'http://localhost:11434/api/generate',
            [
                'model'  => 'llama3:8b',
                'prompt' => $query,
                'num_predict' => 200,
                'stream' => false,
            ]
        );

         if ($response->failed()) {
            throw new \Exception('AI service failed');
        }

        return trim($response->json('response'));;
    }
}