<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $indexExists = DB::select("
            SHOW INDEX FROM students 
            WHERE Key_name = 'students_user_id_unique'
        ");

        if (empty($indexExists)) {
            Schema::table('students', function (Blueprint $table) {
                $table->unique('user_id', 'students_user_id_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_user_id_unique');
        });
    }
};
