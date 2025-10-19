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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel students
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

            // Foreign key ke tabel pegawais
            $table->foreignId('pegawais_id')->constrained('pegawais')->onDelete('cascade');

            // Foreign key ke tabel institutions
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');

            $table->string('filename');
            $table->enum('document_type', ['dokumen_ijazah', 'transkrip', 'skpi']);
            $table->string('hash');
            $table->text('signature');
            $table->string('tx_id')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};