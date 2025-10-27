<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel faculties
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Buat tabel program_studies
        Schema::create('program_studies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('faculty_id')->nullable()->constrained('faculties');
            $table->timestamps();
        });

        // Tambahkan foreign key ke students
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('faculty_id')->after('student_id')->nullable()->constrained('faculties');
            $table->foreignId('program_study_id')->after('faculty_id')->nullable()->constrained('program_studies');

            // Hapus kolom lama
            $table->dropColumn('faculty');
            $table->dropColumn('program_study');
        });
    }

    public function down(): void
    {
        // Tambahkan kembali kolom lama
        Schema::table('students', function (Blueprint $table) {
            $table->string('faculty')->after('student_id');
            $table->string('program_study')->after('faculty');

            $table->dropForeign(['faculty_id']);
            $table->dropColumn('faculty_id');

            $table->dropForeign(['program_study_id']);
            $table->dropColumn('program_study_id');
        });

        Schema::dropIfExists('program_studies');
        Schema::dropIfExists('faculties');
    }
};
