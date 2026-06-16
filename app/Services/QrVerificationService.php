<?php

namespace App\Services;

use App\Helpers\QrEncryption;
use App\Models\QrCode;
use Throwable;

class QrVerificationService
{
    public function verify(?string $uniqueId, bool $recordScan = true): array
    {
        if (blank($uniqueId)) {
            return $this->result('invalid', 'QR code ID is missing.');
        }

        $record = QrCode::where('unique_id', $uniqueId)->first();

        if (! $record) {
            return $this->result('invalid', 'QR code not found. This product may be counterfeit.');
        }

        if ($record->expires_at?->isPast()) {
            return $this->result(
                'expired',
                'This QR code has expired and can no longer be verified.',
                $record
            );
        }

        try {
            $payload = QrEncryption::decrypt($record->encrypted_payload);
        } catch (Throwable) {
            return $this->result(
                'invalid',
                'Unable to verify QR code. The data may be corrupted or tampered with.',
                $record
            );
        }

        if (config('qr.one_time_scan') && $record->scan_count > 0) {
            return $this->result(
                'already_scanned',
                'This QR code has already been verified.',
                $record,
                $payload
            );
        }

        if ($recordScan) {
            $this->recordScan($record);
        }

        return $this->result(
            'verified',
            'Product verified successfully.',
            $record,
            $payload
        );
    }

    private function recordScan(QrCode $record): void
    {
        $now = now();

        $record->update([
            'scan_count' => $record->scan_count + 1,
            'first_scanned_at' => $record->first_scanned_at ?? $now,
            'last_scanned_at' => $now,
        ]);
    }

    private function result(
        string $status,
        string $message,
        ?QrCode $record = null,
        ?array $payload = null
    ): array {
        return compact('status', 'message', 'record', 'payload');
    }
}
