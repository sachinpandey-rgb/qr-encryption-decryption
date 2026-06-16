<?php

namespace App\Services;

use App\Models\QrToken;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class QrTokenService
{
    public function generateForProduct(string $productUuid): QrToken
    {
        return DB::transaction(function () use ($productUuid) {
            return QrToken::create([
                'token' => $this->createUniqueToken(),
                'product_uuid' => $productUuid,
                'status' => QrToken::STATUS_ACTIVE,
            ]);
        });
    }

    public function resolveProductUuid(string $token): ?string
    {
        $record = QrToken::query()
            ->where('token', $token)
            ->first();

        if (! $record || ! $record->isActive()) {
            return null;
        }

        return $record->product_uuid;
    }

    public function buildVerifyUrl(string $token): string
    {
        return config('qr.verify_base_url').'/v/'.$token;
    }

    public function revoke(string $token): bool
    {
        $updated = QrToken::query()
            ->where('token', $token)
            ->where('status', QrToken::STATUS_ACTIVE)
            ->update(['status' => QrToken::STATUS_REVOKED]);

        return $updated > 0;
    }

    public function revokeByProductUuid(string $productUuid): int
    {
        return QrToken::query()
            ->where('product_uuid', $productUuid)
            ->where('status', QrToken::STATUS_ACTIVE)
            ->update(['status' => QrToken::STATUS_REVOKED]);
    }

    private function createUniqueToken(): string
    {
        $bytes = max(16, (int) config('qr.token_bytes', 32));

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $token = rtrim(
                strtr(base64_encode(random_bytes($bytes)), '+/', '-_'),
                '='
            );

            if (! QrToken::where('token', $token)->exists()) {
                return $token;
            }
        }

        throw new RuntimeException('Unable to generate unique QR token.');
    }
}
