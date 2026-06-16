<?php

namespace App\Services;

use Illuminate\Http\Request;

class AppRedirectService
{
    public function isMobile(Request $request): bool
    {
        $userAgent = $request->userAgent() ?? '';

        return (bool) preg_match('/android|iphone|ipad|ipod|mobile/i', $userAgent);
    }

    public function isAndroid(Request $request): bool
    {
        return (bool) preg_match('/android/i', $request->userAgent() ?? '');
    }

    public function buildDeepLinkForToken(string $token): string
    {
        $scheme = config('qr.app_scheme');

        return $scheme.'://verify?token='.urlencode($token);
    }

    public function buildDeepLinkForLegacy(string $uuid, string $signature, int $format): string
    {
        $scheme = config('qr.app_scheme');

        return $scheme.'://verify?u='.urlencode($uuid)
            .'&s='.urlencode($signature)
            .'&f='.$format;
    }

    public function buildAndroidIntentForToken(string $token): string
    {
        $scheme = config('qr.app_scheme');
        $package = config('qr.app_package');
        $fallback = urlencode(config('qr.play_store_url'));
        $tokenParam = urlencode($token);

        return "intent://verify?token={$tokenParam}#Intent;"
            ."scheme={$scheme};"
            ."package={$package};"
            ."S.browser_fallback_url={$fallback};"
            .'end';
    }

    public function buildAndroidIntentForLegacy(string $uuid, string $signature, int $format): string
    {
        $scheme = config('qr.app_scheme');
        $package = config('qr.app_package');
        $fallback = urlencode(config('qr.play_store_url'));

        return 'intent://verify?u='.urlencode($uuid)
            .'&s='.urlencode($signature)
            .'&f='.$format
            .'#Intent;'
            ."scheme={$scheme};"
            ."package={$package};"
            ."S.browser_fallback_url={$fallback};"
            .'end';
    }

    public function buildWebVerifyUrlForToken(string $token): string
    {
        return url('/verify/web?token='.urlencode($token));
    }

    public function buildWebVerifyUrlForLegacy(string $uuid, string $signature, int $format): string
    {
        return url('/verify/web?u='.urlencode($uuid)
            .'&s='.urlencode($signature)
            .'&f='.$format);
    }
}
