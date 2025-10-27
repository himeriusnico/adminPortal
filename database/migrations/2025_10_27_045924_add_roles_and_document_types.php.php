<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya tambah foreign key
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('password')->nullable()->constrained('roles');
            $table->foreignId('institution_id')->after('role_id')->nullable()->constrained('institutions')->unique();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('document_type_id')->after('filename')->nullable()->constrained('document_types');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');

            $table->dropForeign(['institution_id']);
            $table->dropColumn('institution_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['document_type_id']);
            $table->dropColumn('document_type_id');
        });
    }
};
