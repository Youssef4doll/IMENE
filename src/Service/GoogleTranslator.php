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
        // 1. Create a unique cache key based on text and languages
        // We use md5 to make the key safe for the file system
        $cacheKey = 'gtrans_' . md5($text . $targetLang . ($sourceLang ?? 'auto'));

        // 2. Check Cache. If exists, return it. If not, call Google.
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($text, $targetLang, $sourceLang) {
            
            // Cache the result for 30 days so we don't get banned by Google
            $item->expiresAfter(3600 * 24 * 30); 

            // Instantiate the external library
            $tr = new GoogleTranslate();
            $tr->setTarget($targetLang);
            
            if ($sourceLang) {
                $tr->setSource($sourceLang);
            } else {
                $tr->setSource(); // Auto-detect if null
            }

            // Return the translation
            return $tr->translate($text);
        });
    }
}