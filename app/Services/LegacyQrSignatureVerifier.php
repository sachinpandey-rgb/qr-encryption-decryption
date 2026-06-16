<?php

namespace App\Services;

class LegacyQrSignatureVerifier
{
    /**
     * Stage-1 verification for legacy URLs:
     * /qr?u=<uuid>&s=<signature>&f=4
     */
    public function verify(?string $uuid, ?string $signature, int $format = 4): bool
    {
        if (blank($uuid) || blank($signature)) {
            return false;
        }

        $secret = config('qr.sign_secret');

        if (blank($secret)) {
            return false;
        }

        $expected = hash_hmac(
            'sha256',
            $uuid.'|'.$format,
            $secret
        );

        return hash_equals($expected, $signature);
    }

    public function sign(string $uuid, int $format = 4): string
    {
        return hash_hmac(
            'sha256',
            $uuid.'|'.$format,
            config('qr.sign_secret')
        );
    }
}
