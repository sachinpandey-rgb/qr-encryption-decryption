<?php

namespace App\Http\Controllers;

use App\Services\AppRedirectService;
use App\Services\LegacyQrSignatureVerifier;
use App\Services\QrTokenService;
use App\Services\QrVerificationService;
use Illuminate\Http\Request;

class QrVerifyController extends Controller
{
    public function __construct(
        private QrTokenService $tokenService,
        private QrVerificationService $verificationService,
        private LegacyQrSignatureVerifier $legacyVerifier,
        private AppRedirectService $appRedirect,
    ) {}

    public function verifyByToken(Request $request, string $token)
    {
        $productUuid = $this->tokenService->resolveProductUuid($token);

        if (! $productUuid) {
            return $this->invalidResponse('This verification link is invalid or has expired.');
        }

        if ($request->boolean('web') || ! $this->appRedirect->isMobile($request)) {
            return $this->renderVerification($productUuid, $token);
        }

        return $this->appRedirectViewForToken($request, $token);
    }

    public function verifyLegacy(Request $request)
    {
        $uuid = $request->query('u');
        $signature = $request->query('s');
        $format = (int) $request->query('f', config('qr.legacy_format', 4));

        if (! $this->legacyVerifier->verify($uuid, $signature, $format)) {
            return $this->invalidResponse('Verification failed.');
        }

        if ($request->boolean('web') || ! $this->appRedirect->isMobile($request)) {
            return $this->renderVerification($uuid);
        }

        return $this->appRedirectViewForLegacy($request, $uuid, $signature, $format);
    }

    public function verifyWeb(Request $request)
    {
        if ($request->filled('token')) {
            $token = $request->query('token');
            $productUuid = $this->tokenService->resolveProductUuid($token);

            if (! $productUuid) {
                return $this->invalidResponse('This verification link is invalid or has expired.');
            }

            return $this->renderVerification($productUuid, $token);
        }

        $uuid = $request->query('u');
        $signature = $request->query('s');
        $format = (int) $request->query('f', config('qr.legacy_format', 4));

        if (! $this->legacyVerifier->verify($uuid, $signature, $format)) {
            return $this->invalidResponse('Verification failed.');
        }

        return $this->renderVerification($uuid);
    }

    public function revokeToken(string $token)
    {
        if (! $this->tokenService->revoke($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found or already revoked.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token revoked.',
        ]);
    }

    private function appRedirectViewForToken(Request $request, string $token)
    {
        $deepLink = $this->appRedirect->buildDeepLinkForToken($token);
        $androidIntent = $this->appRedirect->buildAndroidIntentForToken($token);

        return view('qrcode.app-redirect', [
            'appName' => config('qr.app_name'),
            'token' => $token,
            'deepLink' => $deepLink,
            'androidIntentUrl' => $androidIntent,
            'primaryOpenUrl' => $this->appRedirect->isAndroid($request) ? $androidIntent : $deepLink,
            'playStoreUrl' => config('qr.play_store_url'),
            'webVerifyUrl' => $this->appRedirect->buildWebVerifyUrlForToken($token),
            'isAndroid' => $this->appRedirect->isAndroid($request),
        ]);
    }

    private function appRedirectViewForLegacy(
        Request $request,
        string $uuid,
        string $signature,
        int $format
    ) {
        $deepLink = $this->appRedirect->buildDeepLinkForLegacy($uuid, $signature, $format);
        $androidIntent = $this->appRedirect->buildAndroidIntentForLegacy($uuid, $signature, $format);

        return view('qrcode.app-redirect', [
            'appName' => config('qr.app_name'),
            'token' => null,
            'deepLink' => $deepLink,
            'androidIntentUrl' => $androidIntent,
            'primaryOpenUrl' => $this->appRedirect->isAndroid($request) ? $androidIntent : $deepLink,
            'playStoreUrl' => config('qr.play_store_url'),
            'webVerifyUrl' => $this->appRedirect->buildWebVerifyUrlForLegacy($uuid, $signature, $format),
            'isAndroid' => $this->appRedirect->isAndroid($request),
        ]);
    }

    private function renderVerification(string $productUuid, ?string $token = null)
    {
        $result = $this->verificationService->verify($productUuid);
        $result['appRedirect'] = $this->buildAppRedirectPayload($token);

        return view('qrcode.scan', $result);
    }

    private function buildAppRedirectPayload(?string $token): array
    {
        return [
            'appName' => config('qr.app_name'),
            'playStoreUrl' => config('qr.play_store_url'),
            'deepLink' => $token
                ? $this->appRedirect->buildDeepLinkForToken($token)
                : config('qr.app_scheme').'://verify',
        ];
    }

    private function invalidResponse(string $message)
    {
        return response()->view('qrcode.scan', [
            'status' => 'invalid',
            'message' => $message,
            'record' => null,
            'payload' => null,
        ], 404);
    }
}
