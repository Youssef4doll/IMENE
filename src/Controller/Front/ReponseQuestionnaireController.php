<?php

namespace App\Controller\Front;

use App\Entity\Agent;
use App\Entity\ReponseQuestionnaire;
use App\Form\ReponseQuestionnaireType;
use App\Repository\QuestionnaireAgentRepository;
use App\Repository\ReponseQuestionnaireRepository;
use App\Service\AiEvaluationService; // ✅ IMPORTANT: Import the AI Service
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/front/agents')]
class ReponseQuestionnaireController extends AbstractController
{
    /**
     * READ: Show the answers AND Run Live AI Analysis
     */
    #[Route('/{id}/questionnaire', name: 'front_agent_questionnaire', methods: ['GET'])]
    public function show(
        Agent $agent,
        QuestionnaireAgentRepository $questionnaireRepo,
        ReponseQuestionnaireRepository $repRepo,
        AiEvaluationService $aiService // ✅ INJECT SERVICE HERE
    ): Response {
        
        // 1. Security Check
        $user = $this->getUser();
        if ($user && method_exists($agent, 'getPlayer') && $agent->getPlayer() !== $user) {
            throw $this->createAccessDeniedException('Not your agent.');
        }

        // 2. Find the Questionnaire for the Agent's Game
        $game = trim((string) $agent->getGame());
        $questionnaire = $questionnaireRepo->findOneBy(['game' => $game]);

        if (!$questionnaire) {
            // If the game doesn't match any questionnaire in the DB
            return $this->render('front/questionnaire_show.html.twig', [
                'agent' => $agent,
                'questionnaire' => null, // Handle null in twig or show error
                'reponse' => null,
                'error' => "Questionnaire not found for game: " . $game
            ]);
        }

        // 3. Find the User's Answers
        $reponse = $repRepo->findOneBy([
            'agent' => $agent,
            'questionnaire' => $questionnaire,
        ]);

        // 4. If no answers, redirect to the Edit page to fill them out
        if (!$reponse) {
            return $this->redirectToRoute('front_agent_questionnaire_edit', ['id' => $agent->getId()]);
        }

        // -----------------------------------------------------------
        // ⚡ LIVE AI EXECUTION
        // -----------------------------------------------------------
        // We calculate the score right now because it is not stored in the DB.
        $aiResult = $aiService->evaluate($questionnaire, $reponse);

        // 5. Render the View
        return $this->render('front/questionnaire_show.html.twig', [
            'agent' => $agent,
            'questionnaire' => $questionnaire,
            'reponse' => $reponse,
            'aiResult' => $aiResult, // ✅ PASS RESULT TO VIEW
        ]);
    }

    /**
     * WRITE: Create or Update answers
     */
    #[Route('/{id}/questionnaire/edit', name: 'front_agent_questionnaire_edit', methods: ['GET', 'POST'])]
    public function edit(
        Agent $agent,
        Request $request,
        QuestionnaireAgentRepository $questionnaireRepo,
        ReponseQuestionnaireRepository $repRepo,
        EntityManagerInterface $em,
        AiEvaluationService $aiService // ✅ INJECT SERVICE HERE
    ): Response {
        
        $user = $this->getUser();
        if ($user && method_exists($agent, 'getPlayer') && $agent->getPlayer() !== $user) {
            throw $this->createAccessDeniedException('Not your agent.');
        }

        $game = trim((string) $agent->getGame());
        $currentQuestionnaire = $questionnaireRepo->findOneBy(['game' => $game]);

        if (!$currentQuestionnaire) {
            $this->addFlash('danger', 'No questionnaire found for ' . $game);
            return $this->redirectToRoute('front_agent_index');
        }

        $reponse = $repRepo->findOneBy(['agent' => $agent]);

        if (!$reponse) {
            $reponse = new ReponseQuestionnaire();
            $reponse->setAgent($agent);
            $reponse->setQuestionnaire($currentQuestionnaire);
        } else {
            $reponse->setQuestionnaire($currentQuestionnaire);
        }

        $form = $this->createForm(ReponseQuestionnaireType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setAgent($agent);
            $reponse->setQuestionnaire($currentQuestionnaire);

            $em->persist($reponse);
            $em->flush();

            // ✅ RUN AI ON SUBMIT
            $aiResult = $aiService->evaluate($currentQuestionnaire, $reponse);
            
            $this->addFlash('success', 'Answers saved & Evaluated by Coach AI!');

            // ✅ RENDER DIRECTLY (Do not redirect) to show the AI result immediately
            return $this->render('front/questionnaire_show.html.twig', [
                'agent' => $agent,
                'questionnaire' => $currentQuestionnaire,
                'reponse' => $reponse,
                'aiResult' => $aiResult 
            ]);
        }

        return $this->render('front/questionnaire.html.twig', [
            'agent' => $agent,
            'questionnaire' => $currentQuestionnaire,
            'form' => $form->createView(),
        ]);
    }
}