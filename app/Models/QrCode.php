<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QrCode extends Model
{
    protected $fillable = [
        'unique_id',
        'encrypted_payload',
        'qr_image',
        'expires_at',
        'scan_count',
        'first_scanned_at',
        'last_scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'first_scanned_at' => 'datetime',
            'last_scanned_at' => 'datetime',
        ];
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(QrToken::class, 'product_uuid', 'unique_id');
    }

    public function activeToken(): HasOne
    {
        return $this->hasOne(QrToken::class, 'product_uuid', 'unique_id')
            ->where('status', QrToken::STATUS_ACTIVE)
            ->latestOfMany();
    }
}
