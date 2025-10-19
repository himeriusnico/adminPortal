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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            
            // Definisikan 'users_id' sebagai foreign key ke tabel 'users'
            $table->foreignId('users_id')->unique()->constrained('users')->onDelete('cascade');
            
            // Definisikan 'institution_id' sebagai foreign key ke tabel 'institutions'
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            
            $table->string('employee_id')->unique();
            $table->string('position');
            $table->string('department')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};