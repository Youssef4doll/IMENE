<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::REQUEST, priority: 20)] // <--- THIS LINE FIXES IT
class LocaleListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // 1. Check if user clicked a language link (e.g. ?lang=fr)
        if ($lang = $request->query->get('lang')) {
            $request->getSession()->set('lang', $lang);
        }
        
        // 2. Ensure session has a default language if none exists
        if (!$request->getSession()->has('lang')) {
            $request->getSession()->set('lang', 'en');
        }
    }
}