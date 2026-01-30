<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('document_type'); // Hapus kolom ENUM yang lama
        });
    }

    public function down()
    {
        // Jika Anda ingin rollback, tambahkan kolom ENUM kembali di sini
        Schema::table('documents', function (Blueprint $table) {
            $table->enum('document_type', ['dokumen_ijazah', 'transkrip', 'skpi'])->after('document_type_id')->nullable();
        });
    }
};
