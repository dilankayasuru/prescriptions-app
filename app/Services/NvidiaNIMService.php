<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NvidiaNIMService
{
    protected string $apiUrl = "https://integrate.api.nvidia.com/v1/chat/completions";
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env("NVIDIA_API_KEY");
    }

    public function extractFromImage(string $imagePath, bool $stream = false): mixed
    {
        // Encode image to base64
        $imageData = base64_encode(file_get_contents($imagePath));

        $systemMessage = <<<SYS
You are a precise medical prescription parser.
Your only job is to extract medicine names and dosages from prescriptions.

### Rules:
1. Output must be a valid JSON array of objects.
2. Each object = { "medicine": string, "dosage": string }.
3. Return [] if no medicines are found.
4. Do not include explanations or extra text.
SYS;

        $userPrompt = <<<USR
Here is the prescription image:
<img src="data:image/png;base64,{$imageData}" />
USR;

        $headers = [
            "Authorization" => "Bearer {$this->apiKey}",
            "Accept" => $stream ? "text/event-stream" : "application/json",
        ];

        $payload = [
            "model" => "meta/llama-3.2-11b-vision-instruct",
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
            "stream" => $stream,
        ];

        $response = Http::withHeaders($headers)->post($this->apiUrl, $payload);

        if ($response->failed()) {
            throw new \Exception("NVIDIA API call failed: " . $response->body());
        }

        if ($stream) {
            return $response->body();
        } else {
            return $response->json();
        }
    }
}
