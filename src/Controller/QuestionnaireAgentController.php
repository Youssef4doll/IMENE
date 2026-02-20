<?php

namespace App\Controller;

use App\Entity\QuestionnaireAgent;
use App\Form\QuestionnaireAgentType;
use App\Repository\QuestionnaireAgentRepository;
use App\Repository\AgentRepository;                // ✅ Required for Stats
use App\Repository\ReponseQuestionnaireRepository; // ✅ Required for Stats
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/questionnaire/agent')]
final class QuestionnaireAgentController extends AbstractController
{
    #[Route('/', name: 'app_questionnaire_agent_index', methods: ['GET'])]
    public function index(
        Request $request, 
        QuestionnaireAgentRepository $repository,
        AgentRepository $agentRepository,               
        ReponseQuestionnaireRepository $reponseRepository 
    ): Response
    {
        // 1. Search & Sort
        $q = $request->query->get('q');
        $sort = $request->query->get('sort', 'game');
        $dir = $request->query->get('dir', 'ASC');
        $questionnaires = $repository->searchAndSort($q, $sort, $dir);

        // 2. CALCULATE KPI STATS
        $totalAgents = $agentRepository->count([]);
        $totalResponses = $reponseRepository->count([]);
        
        $completionRate = 0;
        if ($totalAgents > 0) {
            $completionRate = round(($totalResponses / $totalAgents) * 100, 1);
        }

        return $this->render('questionnaire_agent/index.html.twig', [
            'questionnaire_agents' => $questionnaires,
            'q' => $q, 
            'sort' => $sort, 
            'dir' => $dir,
            
            // ✅ Pass Stats to Template
            'total_agents' => $totalAgents,
            'total_responses' => $totalResponses,
            'completion_rate' => $completionRate,
        ]);
    }

    #[Route('/new', name: 'app_questionnaire_agent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $questionnaireAgent = new QuestionnaireAgent();
        $form = $this->createForm(QuestionnaireAgentType::class, $questionnaireAgent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($questionnaireAgent);
            $entityManager->flush();
            return $this->redirectToRoute('app_questionnaire_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('questionnaire_agent/new.html.twig', [
            'questionnaire_agent' => $questionnaireAgent,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_questionnaire_agent_show', methods: ['GET'])]
    public function show(QuestionnaireAgent $questionnaireAgent): Response
    {
        return $this->render('questionnaire_agent/show.html.twig', [
            'questionnaire_agent' => $questionnaireAgent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_questionnaire_agent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuestionnaireAgent $questionnaireAgent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionnaireAgentType::class, $questionnaireAgent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_questionnaire_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('questionnaire_agent/edit.html.twig', [
            'questionnaire_agent' => $questionnaireAgent,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_questionnaire_agent_delete', methods: ['POST'])]
    public function delete(Request $request, QuestionnaireAgent $questionnaireAgent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionnaireAgent->getId(), $request->request->get('_token'))) {
            $entityManager->remove($questionnaireAgent);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_questionnaire_agent_index', [], Response::HTTP_SEE_OTHER);
    }
}