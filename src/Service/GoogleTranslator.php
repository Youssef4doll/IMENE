<?php

namespace App\Service;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class GoogleTranslator
{
    private $cache;

    public function __construct(CacheInterface $appCache)
    {
        $this->cache = $appCache;
    }

    public function translate(string $text, string $targetLang, string $sourceLang = null): string
    {
        if (empty(trim($text)) || $targetLang === 'fr') {
            return $text;
        }

        $cacheKey = 'gtrans_' . md5($text . $targetLang . ($sourceLang ?? 'auto'));

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($text, $targetLang, $sourceLang) {
                $item->expiresAfter(3600 * 24 * 30); 

                $tr = new GoogleTranslate();
                $tr->setTarget($targetLang);
                
                if ($sourceLang) {
                    $tr->setSource($sourceLang);
                }

                return $tr->translate($text);
            });
        } catch (\Exception $e) {
            // ✅ SAFETY CATCH: If Google blocks us or fails, just return the normal text so the site doesn't crash!
            return $text;
        }
    }
}