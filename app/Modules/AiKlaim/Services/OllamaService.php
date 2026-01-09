<?php

namespace App\Modules\AiKlaim\Services;

use Illuminate\Support\Facades\Http;

class OllamaService
{
    public static function generate($prompt)
    {
        return Http::timeout(120)->post(
            config('services.ollama.url').'/api/generate',
            [
                'model' => config('services.ollama.model'),
                'prompt' => $prompt,
                'stream' => false
            ]
        )->json()['response'] ?? null;
    }
}

