<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptionDecryptionService
{
    public function encrypt($data){

        $encryptedPayload = Crypt::encryptString(json_encode($data));

        $signature = hash_hmac('sha256', $encryptedPayload, env('QR_SIGN_SECRET'));

        $qrPayload = json_encode([
            'payload' => $encryptedPayload,
            'signature' => $signature
        ]);

        return $qrPayload;
    }

    
    public function decrypt($qrData){

        $qr = json_decode($qrData, true);

        $payload = $qr['payload'];

        $receivedSignature = $qr['signature'];

        $expectedSignature =  hash_hmac('sha256', $payload, env('QR_SIGN_SECRET'));
      
        if (!hash_equals($expectedSignature, $receivedSignature)) {
            return [
                'status' => false,
                'message' => 'Invalid data'
            ];
        }

        $decrypted = Crypt::decryptString($payload);

        $data = json_decode($decrypted,true);

        return $data;
    }
}