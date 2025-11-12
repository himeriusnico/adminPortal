<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('encrypted_keys', function (Blueprint $table) {
            $table->id();

            // Relation to institutions
            $table->foreignId('institution_id')
                  ->constrained('institutions')
                  ->onDelete('cascade');

            // Encrypted private key (Base64 encoded)
            $table->longText('encrypted_private_key');

            // Salt used for PBKDF2 / key derivation
            $table->binary('salt');

            // Initialization vector used for AES encryption
            $table->binary('iv');

            // PBKDF2 iteration count, default = 100,000
            $table->integer('iterations')->default(100000);

            // Optional key type (future-proofing)
            $table->string('key_type', 20)->default('ecdsa');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encrypted_keys');
    }
};
