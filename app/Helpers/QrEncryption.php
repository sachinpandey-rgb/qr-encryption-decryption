<?php

namespace App\Helpers;

class QrEncryption
{
    public static function encrypt(array $data): string
    {
        $secretKey = config('qr.secret_key');

        $key = hash('sha256', $secretKey, true);

        $iv = random_bytes(16);

        $encrypted = openssl_encrypt(
            json_encode($data),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode(
            json_encode([
                'iv' => base64_encode($iv),
                'data' => base64_encode($encrypted)
            ])
        );
    }

    public static function decrypt(string $payload): array
    {
        $secretKey = config('qr.secret_key');

        $key = hash('sha256', $secretKey, true);

        $decoded = json_decode(
            base64_decode($payload),
            true
        );

        $iv = base64_decode($decoded['iv']);

        $encryptedData = base64_decode(
            $decoded['data']
        );

        $decrypted = openssl_decrypt(
            $encryptedData,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return json_decode($decrypted, true);
    }
}