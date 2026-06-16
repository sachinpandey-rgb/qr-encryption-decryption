<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('qr_image');
            $table->unsignedInteger('scan_count')->default(0)->after('expires_at');
            $table->timestamp('first_scanned_at')->nullable()->after('scan_count');
            $table->timestamp('last_scanned_at')->nullable()->after('first_scanned_at');
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropColumn([
                'expires_at',
                'scan_count',
                'first_scanned_at',
                'last_scanned_at',
            ]);
        });
    }
};
