<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NvidiaNIMService
{
    protected string $apiUrl = "https://integrate.api.nvidia.com/v1/chat/completions";
    protected string $apiKey;

    public function __construct()
    {
        $apiKey = config('services.nvidia.api_key');

        if (empty($apiKey)) {
            throw new \Exception("NVIDIA_API_KEY environment variable is not set or is empty.");
        }

        $this->apiKey = $apiKey;
    }

    public function extractFromImage(string $imagePath): mixed
    {
        $imageData = base64_encode(file_get_contents('storage/' . $imagePath));

        $systemMessage = <<<SYS
You are a medical prescription parser. Extract medicine names and dosages from prescription images.

CRITICAL INSTRUCTIONS:
- Return ONLY a valid JSON array
- Each object format: {"medicine": "name", "dosage": "amount"}
- Example: [{"medicine": "Penicillins", "dosage": "500mg"}, {"medicine": "Amoxicillin", "dosage": "500mg"}]
- If no medicines found, return: []
- NO explanations, NO additional text, NO markdown formatting
- ONLY the JSON array as raw output
SYS;

        $userPrompt = <<<USR
Extract all medicines and dosages from this prescription image and return only the JSON array:
<img src="data:image/png;base64,{$imageData}" />
USR;

        $headers = [
            "Authorization" => "Bearer {$this->apiKey}",
            "Accept" => "application/json",
        ];

        $payload = [
            "model" => "mistralai/mistral-medium-3-instruct",
            "messages" => [
                [
                    "role" => "system",
                    "content" => $systemMessage,
                ],
                [
                    "role" => "user",
                    "content" => $userPrompt,
                ],
            ],
            "max_tokens" => 512,
            "temperature" => 1.0,
            "top_p" => 1.0,
            "frequency_penalty" => 0.0,
            "presence_penalty" => 0.0,
            "stream" => false,
        ];

        $response = Http::withHeaders($headers)->post($this->apiUrl, $payload);

        if ($response->failed()) {
            throw new \Exception("NVIDIA API call failed: " . $response->body());
        }
        $responseData = $response->json();
        $content = $responseData['choices'][0]['message']['content'] ?? '[]';
        return json_decode($content, true);
    }
}
