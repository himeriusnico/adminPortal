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
        Schema::table('institutions', function (Blueprint $table) {
            // Add the column (nullable since not all institutions may have keys yet)
            $table->foreignId('encrypted_key_id')
                ->nullable()
                ->after('public_key')
                ->constrained('encrypted_keys')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropForeign(['encrypted_key_id']);
            $table->dropColumn('encrypted_key_id');
        });
    }
};
