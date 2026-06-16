<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->uuid('product_uuid');
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->index('product_uuid');
            $table->index(['token', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_tokens');
    }
};
