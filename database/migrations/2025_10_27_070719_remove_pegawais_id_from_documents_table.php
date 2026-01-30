<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'pegawais_id')) {
                // $table->dropForeign(['pegawais_id']); // remove foreign key first
                $table->dropColumn('pegawais_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('pegawais_id')->nullable()->after('student_id');
            $table->foreign('pegawais_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
