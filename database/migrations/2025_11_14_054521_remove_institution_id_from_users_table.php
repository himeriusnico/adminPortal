<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Kalau FK ada, drop dulu
            if (Schema::hasColumn('users', 'institution_id')) {
                try {
                    $table->dropForeign(['institution_id']);
                } catch (\Exception $e) {
                    // ignore kalau FK sudah tidak ada
                }

                // Hapus kolom
                $table->dropColumn('institution_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('institution_id')->nullable();
            // Optional: FK kalau mau rollback full
            // $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
        });
    }
};
