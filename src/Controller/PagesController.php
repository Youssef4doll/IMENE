<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/champions', name: 'app_champions')]
    public function champions(): Response
    {
        return $this->render('pages/champions.html.twig');
    }

    #[Route('/teams', name: 'app_teams')]
    public function teams(): Response
    {
        return $this->render('pages/teams.html.twig');
    }

    #[Route('/matches', name: 'app_matches')]
    public function matches(): Response
    {
        return $this->render('pages/matches.html.twig');
    }

    #[Route('/tournaments', name: 'app_tournaments')]
    public function tournaments(): Response
    {
        return $this->render('pages/tournaments.html.twig');
    }

    #[Route('/coaching', name: 'app_coaching')]
    public function coaching(): Response
    {
        return $this->render('pages/coaching.html.twig');
    }

    #[Route('/shop', name: 'app_shop')]
    public function shop(): Response
    {
        return $this->render('pages/shop.html.twig');
    }

    #[Route('/help', name: 'app_help')]
    public function help(): Response
    {
        return $this->render('pages/help.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }

    #[Route('/faq', name: 'app_faq')]
    public function faq(): Response
    {
        return $this->render('pages/faq.html.twig');
    }

    #[Route('/newsletter/subscribe', name: 'app_newsletter_subscribe', methods: ['POST'])]
    public function newsletterSubscribe(): Response
    {
        // later you can implement logic; for now just redirect home
        return $this->redirectToRoute('app_home');
    }

    #[Route('/legal', name: 'app_legal')]
    public function legal(): Response
    {
        return $this->render('pages/legal.html.twig');
    }

    #[Route('/privacy', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('pages/privacy.html.twig');
    }

    #[Route('/cookies', name: 'app_cookies')]
    public function cookies(): Response
    {
        return $this->render('pages/cookies.html.twig');
    }

    #[Route('/terms', name: 'app_terms')]
    public function terms(): Response
    {
        return $this->render('pages/terms.html.twig');
    }
}
