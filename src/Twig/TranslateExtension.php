<?php

namespace App\Twig;

use App\Service\GoogleTranslator;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslateExtension extends AbstractExtension
{
    private $translator;
    private $requestStack;

    public function __construct(GoogleTranslator $translator, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    public function getFilters(): array
    {
        return [
            // This registers the 'gtrans' filter in Twig
            new TwigFilter('gtrans', [$this, 'autoTranslate']),
        ];
    }
public function autoTranslate(string $text): string
    {
        $request = $this->requestStack->getCurrentRequest();
        
        // 👇 Read directly from the session instead of the request!
        $targetLocale = 'fr'; // Default
        if ($request && $request->hasSession()) {
            $targetLocale = $request->getSession()->get('_locale', 'fr');
        }

        // If the text is empty or the target is French, don't translate
        if (empty(trim($text)) || $targetLocale === 'fr') {
            return $text;
        }

        // Call Google Translate
        return $this->translator->translate($text, $targetLocale, 'fr');
    }
}