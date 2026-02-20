<?php

namespace App\Service;

use App\Repository\AgentRepository;
use App\Repository\ReponseQuestionnaireRepository;

class BadgeService
{
    public function __construct(
        private AgentRepository $agentRepo,
        private ReponseQuestionnaireRepository $repRepo
    ) {}

    public function getBadges(): array
    {
        $agentsCount = $this->agentRepo->count([]);
        $responsesCount = $this->repRepo->count([]);

        $distinctGamesAnswered = method_exists($this->repRepo, 'countDistinctGamesAnswered')
            ? $this->repRepo->countDistinctGamesAnswered()
            : 0;

        $noSkipsCount = method_exists($this->repRepo, 'countNoSkips')
            ? $this->repRepo->countNoSkips()
            : 0;

        // ✅ Put images in /public/images/badges/
        return [
            $this->badge(
                'profile_starter',
                'Profile Starter',
                'At least 1 agent exists',
                $agentsCount >= 1,
                'images/badges/profile_front.jpg',
                'images/badges/profile_back.jpg'
            ),
            $this->badge(
                'agent_trio',
                'Agent Trio',
                'At least 3 agents exist',
                $agentsCount >= 3,
                'images/badges/trio_front.jpg',
                'images/badges/trio_back.jpg'
            ),
            $this->badge(
                'questionnaire_done',
                'Questionnaire Done',
                'At least 1 response exists',
                $responsesCount >= 1,
                'images/badges/questionnaire_front.jpg',
                'images/badges/questionnaire_back.jpg'
            ),
            $this->badge(
                'multi_game',
                'Multi-Game Explorer',
                'Answered 2 different games',
                $distinctGamesAnswered >= 2,
                'images/badges/multigame_front.jpg',
                'images/badges/multigame_back.jpg'
            ),
            $this->badge(
                'no_skips',
                'No Skips',
                'A response with rep3 & rep4 filled',
                $noSkipsCount >= 1,
                'images/badges/noskips_front.jpg',
                'images/badges/noskips_back.jpg'
            ),
        ];
    }

    private function badge(
        string $code,
        string $title,
        string $desc,
        bool $earned,
        ?string $frontImage,
        ?string $backImage
    ): array {
        return [
            'code' => $code,
            'title' => $title,
            'desc' => $desc,
            'earned' => $earned,
            'frontImage' => $frontImage,
            'backImage' => $backImage,
        ];
    }
}
