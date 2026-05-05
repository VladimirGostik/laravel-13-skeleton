<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\SupportedLanguage;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class LocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolve($request);

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }

    private function resolve(Request $request): string
    {
        $user = $request->user();
        if ($user && SupportedLanguage::isSupported((string) $user->locale)) {
            return $user->locale;
        }

        $session = $request->session()->get('locale');
        if (is_string($session) && SupportedLanguage::isSupported($session)) {
            return $session;
        }

        $cookie = $request->cookie('locale');
        if (is_string($cookie) && SupportedLanguage::isSupported($cookie)) {
            return $cookie;
        }

        $preferred = $request->getPreferredLanguage(SupportedLanguage::getCodes());
        if (is_string($preferred) && SupportedLanguage::isSupported($preferred)) {
            return $preferred;
        }

        return SupportedLanguage::getDefault()->value;
    }
}
