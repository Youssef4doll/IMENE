<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/back', name: 'app_back_dashboard')]
    public function index(): Response
    {
        return $this->render('pages/dashboard.html.twig');

    }
}
