<?php

namespace App\Controller\Front;

use App\Entity\Agent;
use App\Form\AgentEditType;
use App\Form\FrontAgentCreateType;
use App\Repository\AgentRepository;
use App\Repository\ReponseQuestionnaireRepository;
use App\Repository\QuestionnaireAgentRepository; 
use App\Service\AiEvaluationService;            
use App\Service\DiscordScoutService; // ✅ Added import here
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\BadgeService;

// QR Code Imports
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\RoundBlockSizeMode;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/front/agents')]
class AgentProfileController extends AbstractController
{
    #[Route('/', name: 'front_agent_index', methods: ['GET'])]
    public function index(
        AgentRepository $repo,
        ReponseQuestionnaireRepository $repRepo,
        BadgeService $badgeService,
        Request $request
    ): Response
    {
        $q = $request->query->get('q');
        $sort = $request->query->get('sort', 'id');
        $dir = $request->query->get('dir', 'ASC');

        $agents = $repo->searchAndSort($q, $sort, $dir);
        $badges = $badgeService->getBadges();

        $answeredIds = [];
        if (method_exists($repRepo, 'findAnsweredAgentIds')) {
            $answeredIds = $repRepo->findAnsweredAgentIds();
        }

        return $this->render('front/index.html.twig', [
            'agents' => $agents,
            'answeredIds' => $answeredIds,
            'q' => $q,
            'sort' => $sort,
            'dir' => $dir,
            'badges' => $badges,
            'pageTitle' => "Mes Profils d'Agent",
            'searchPlaceholder' => "Rechercher (Pseudo, Jeu...)",
        ]);
    }

    #[Route('/badges', name: 'front_badges', methods: ['GET'])]
    public function badges(BadgeService $badgeService): Response
    {
        return $this->render('front/badges.html.twig', [
            'badges' => $badgeService->getBadges(),
        ]);
    }
    
    #[Route('/new', name: 'front_agent_new', methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $em, AgentRepository $repo): Response
    {
        $agent = new Agent();
        $agent->setDateOfCreation(new \DateTime());

        $form = $this->createForm(FrontAgentCreateType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existing = $repo->findOneBy([
                'player' => $agent->getPlayer(),
                'game'   => $agent->getGame(),
            ]);

            if ($existing) {
                $this->addFlash('danger', 'You already created an agent for this game.');
                return $this->redirectToRoute('front_agent_new');
            }

            $em->persist($agent);
            $em->flush();

            $this->addFlash('success', 'Agent created.');
            return $this->redirectToRoute('front_agent_index');
        }

        return $this->render('front/agent_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'front_agent_show', methods: ['GET'])]
    public function show(
        Agent $agent, 
        ReponseQuestionnaireRepository $repRepo,
        QuestionnaireAgentRepository $questRepo,
        AiEvaluationService $aiService,
        DiscordScoutService $discordScout, // ✅ Cleaned up parameter
        Request $request
    ): Response
    {
        // 1. Get Response & Questionnaire
        $reponse = $repRepo->findOneBy(['agent' => $agent]);
        
        // 2. AI LOGIC: Only run if user clicked the button (?analyze=1)
        $aiResult = null;
        $runAi = $request->query->get('analyze'); 

        // ✅ FIXED: Missing Brackets were here
        if ($runAi && $reponse) {
            $game = trim((string) $agent->getGame());
            $questionnaire = $questRepo->findOneBy(['game' => $game]);
            
            if ($questionnaire) {
                // RUN AI NOW
                $userLanguage = $request->getSession()->get('_locale', 'fr');
                $aiResult = $aiService->evaluate($questionnaire, $reponse, $request->getLocale());
                
                // --- NEW: DISCORD SCOUT ALERT ---
                if (isset($aiResult['score']) && $aiResult['score'] >= 80) {
                    $profileUrl = $this->generateUrl(
                        'front_agent_show', 
                        ['id' => $agent->getId()], 
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );
                    
                    $discordScout->sendHighScorerAlert(
                        $agent->getPseudo(), 
                        $game, 
                        $aiResult['score'], 
                        $profileUrl
                    );
                }
            } // ✅ Closed $questionnaire IF
        } // ✅ Closed $runAi IF

        // 3. QR Code Logic
        $url = $agent->getSocialsLink();
        if (empty($url)) {
            $url = $this->generateUrl('front_agent_show', ['id' => $agent->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 200,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $writer = new SvgWriter();
        $result = $writer->write($qrCode);
        $qrCodeDataUri = $result->getDataUri();

        // 4. Render
        return $this->render('front/show.html.twig', [
            'agent' => $agent,
            'reponse' => $reponse,
            'qrCode' => $qrCodeDataUri, 
            'aiResult' => $aiResult, 
        ]);
    }

    #[Route('/{id}/edit', name: 'front_agent_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Agent $agent, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AgentEditType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Agent updated.');
            return $this->redirectToRoute('front_agent_index');
        }

        return $this->render('front/edit.html.twig', [
            'agent' => $agent,
            'form'  => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'front_agent_delete', methods: ['POST'])]
    public function delete(Request $request, Agent $agent, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_agent_' . $agent->getId(), $request->request->get('_token'))) {
            $em->remove($agent);
            $em->flush();
            $this->addFlash('success', 'Agent deleted.');
        }

        return $this->redirectToRoute('front_agent_index');
    }
}