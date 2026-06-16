<?php

namespace App\Http\Controllers;

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
    ) {}

    public function verifyByToken(string $token)
    {
        $productUuid = $this->tokenService->resolveProductUuid($token);

        if (! $productUuid) {
            return $this->invalidResponse('This verification link is invalid or has expired.');
        }

        return $this->renderVerification($productUuid);
    }

    public function verifyLegacy(Request $request)
    {
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

    private function renderVerification(string $productUuid)
    {
        $result = $this->verificationService->verify($productUuid);

        return view('qrcode.scan', $result);
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
