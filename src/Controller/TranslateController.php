<?php

namespace App\Controller;

use App\Service\GoogleTranslator; // ✅ Use YOUR service
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TranslateController extends AbstractController
{
    #[Route('/translate', name: 'app_translate')]
    public function translate(GoogleTranslator $translator): Response
    {
        // Use your service (which handles the caching automatically)
        // Example: Translate "Hello World" to French ('fr')
        $translatedText = $translator->translate('Hello world', 'fr');

        return new Response("Original: Hello world <br> Translated: " . $translatedText);
    }
}