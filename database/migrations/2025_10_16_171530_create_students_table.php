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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            // Foreign key ke tabel institutions
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');

            $table->string('student_id')->unique();
            $table->string('phone')->nullable();
            $table->string('program_study');
            $table->string('faculty');
            $table->year('entry_year');
            $table->enum('status', ['active', 'graduated', 'inactive'])->default('active');
            $table->date('graduation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};