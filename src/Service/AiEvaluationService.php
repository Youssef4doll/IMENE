<?php

namespace App\Service;

use App\Entity\QuestionnaireAgent;
use App\Entity\ReponseQuestionnaire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiEvaluationService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey; 
    }

    public function evaluate(QuestionnaireAgent $questions, ReponseQuestionnaire $answers): array
    {
        $game = $questions->getGame();
        
        // 1. Build Prompt with ALL Questions
        $prompt = "You are an Esports Coach for '$game'. Evaluate this application.\n";
        $prompt .= "Q1: " . $questions->getQues1() . "\nA1: " . $answers->getRep1() . "\n";
        $prompt .= "Q2: " . $questions->getQues2() . "\nA2: " . $answers->getRep2() . "\n";
        
        // Add Q3 and Q4 if they exist
        if ($questions->getQues3()) {
            $prompt .= "Q3: " . $questions->getQues3() . "\nA3: " . $answers->getRep3() . "\n";
        }
        if ($questions->getQues4()) {
            $prompt .= "Q4: " . $questions->getQues4() . "\nA4: " . $answers->getRep4() . "\n";
        }

        $prompt .= "\nAnalyze the player. Return ONLY raw JSON (no markdown). \n";
        $prompt .= "Format: { \"score\": 85, \"global_feedback\": \"...\", \"suggestions\": { \"rep1\": \"...\", \"rep2\": \"...\", \"rep3\": \"...\", \"rep4\": \"...\" } }";

        // ✅ Using 'gemini-flash-latest' for speed
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . $this->apiKey;

        try {
            $response = $this->client->request('POST', $url, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ],
                'verify_peer' => false, 
                'verify_host' => false,
            ]);

            if ($response->getStatusCode() !== 200) {
                 return [
                    'score' => 0,
                    'global_feedback' => '⚠️ API Error: ' . $response->getStatusCode(),
                    'suggestions' => []
                ];
            }

            $data = $response->toArray();
            
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                 return [
                    'score' => 0,
                    'global_feedback' => '⚠️ AI returned no text.',
                    'suggestions' => []
                ];
            }

            $rawText = $data['candidates'][0]['content']['parts'][0]['text'];
            $rawText = preg_replace('/^```json\s*|\s*```$/', '', $rawText);
            
            return json_decode($rawText, true) ?? [];

        } catch (\Exception $e) {
            return [
                'score' => 0,
                'global_feedback' => '⚠️ System Error: ' . $e->getMessage(),
                'suggestions' => []
            ];
        }
    }
}