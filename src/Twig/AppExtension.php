<?php

namespace App\Twig;

use App\Service\GoogleTranslator;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private $translator;
    private $requestStack;

    // Inject RequestStack to access the Session
    public function __construct(GoogleTranslator $translator, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('gtrans', [$this, 'translateText']),
        ];
    }

    public function translateText(string $text, string $targetLang = null): string
    {
        // 1. If no language provided, get it from Session (default to 'en')
        if ($targetLang === null) {
            $session = $this->requestStack->getSession();
            $targetLang = $session->get('lang', 'en');
        }

        // 2. Don't translate if empty or target is same as source (assuming source is 'en' or 'fr')
        // Adjust 'en' below to whatever your default database language is
        if (empty($text) || $targetLang === 'en') { 
            return $text;
        }

        try {
            return $this->translator->translate($text, $targetLang);
        } catch (\Exception $e) {
            return $text;
        }
    }
}