<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class DiscordScoutService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function sendHighScorerAlert(string $pseudo, string $game, int $score, string $profileUrl): void
    {
        // 1. Get the webhook URL from the .env file
        $webhookUrl = $_ENV['DISCORD_WEBHOOK_URL'] ?? null;
        
        if (!$webhookUrl) {
            return; // Do nothing if the URL isn't set up yet
        }

        // 2. Send the HTTP POST request to Discord
        $this->client->request('POST', $webhookUrl, [
            'json' => [
                'username' => 'Phantom Scout',
                'avatar_url' => 'https://cdn-icons-png.flaticon.com/512/4712/4712139.png', // Cool robot icon
                'embeds' => [
                    [
                        'title' => '🚨 NOUVEAU TALENT DÉTECTÉ 🚨',
                        'description' => "**{$pseudo}** vient de passer l'évaluation du Coach IA !",
                        'color' => 16724787, // Phantom Force Red
                        'fields' => [
                            ['name' => '🎮 Jeu', 'value' => $game, 'inline' => true],
                            ['name' => '🎯 Score IA', 'value' => "{$score}/100", 'inline' => true],
                            ['name' => '🔗 Lien', 'value' => "[Voir le profil complet]({$profileUrl})", 'inline' => false],
                        ]
                    ]
                ]
            ]
        ]);
    }
}