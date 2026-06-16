<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'unique_id',
        'encrypted_payload',
        'qr_image'
    ];
}
