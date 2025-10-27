<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, pegawai, student
            $table->timestamps();
        });

        // Users table
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->foreignId('role_id')->constrained('roles'); // relation to roles
        //     $table->string('remember_token', 100)->nullable();
        //     $table->timestamps();
        // });

        // Institutions table
        // Schema::create('institutions', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email');
        //     $table->text('public_key');
        //     $table->text('ca_cert');
        //     $table->timestamps();
        // });

        // Students table
        // Schema::create('students', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->unique()->constrained('users');
        //     $table->foreignId('institution_id')->constrained('institutions');
        //     $table->string('student_id')->unique();
        //     $table->string('phone')->nullable();
        //     $table->string('program_study');
        //     $table->string('faculty');
        //     $table->year('entry_year');
        //     $table->enum('status', ['active', 'graduated', 'inactive'])->default('active');
        //     $table->date('graduation_date')->nullable();
        //     $table->timestamps();
        // });

        // Document types table
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // contoh: dokumen_ijazah, transkrip, skpi
            $table->timestamps();
        });

        // Documents table
        // Schema::create('documents', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('student_id')->constrained('students');
        //     $table->foreignId('pegawais_id')->nullable()->constrained('users');
        //     $table->foreignId('institution_id')->constrained('institutions');
        //     $table->string('filename');
        //     $table->foreignId('document_type_id')->constrained('document_types');
        //     $table->string('hash');
        //     $table->text('signature');
        //     $table->string('tx_id')->nullable();
        //     $table->string('file_path')->nullable();
        //     $table->timestamps();
        // });

        // Transactions table (misal blockchain detail)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents');
            $table->string('tx_hash')->unique();
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });

        // Failed jobs (standar laravel)
        // Schema::create('failed_jobs', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('uuid')->unique();
        //     $table->text('connection');
        //     $table->text('queue');
        //     $table->longText('payload');
        //     $table->longText('exception');
        //     $table->timestamp('failed_at')->useCurrent();
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('students');
        Schema::dropIfExists('institutions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
