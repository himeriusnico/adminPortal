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
        Schema::table('program_studies', function (Blueprint $table) {
            $table->unsignedBigInteger('university_id')->nullable()->after('faculty_id');

            // Tambahkan foreign key constraint
            $table->foreign('university_id')
                ->references('id')
                ->on('institutions')
                ->onDelete('cascade'); // opsional: jika universitas dihapus, hapus juga program studi
        });
    }

    public function down(): void
    {
        Schema::table('program_studies', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn('university_id');
        });
    }
};
